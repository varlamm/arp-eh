<?php
namespace Xcelerate\Models\Crm\Providers\Zoho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Xcelerate\Models\BatchUpload;
use Xcelerate\Models\BatchUploadRecord;
use Xcelerate\Models\Company;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\Crm\Providers\CrmAbstract;
use Xcelerate\Models\Currency;
use Xcelerate\Models\Item;
use Xcelerate\Models\Unit;
use Xcelerate\Models\ZohoToken;
use Xcelerate\Models\CompanyField;
use Xcelerate\Models\CrmStandardMapping;
use Xcelerate\Models\RequestLog;

class ZohoCrm extends CrmAbstract
{
    public static $access_token;
    public static $api_domain;
    public static $company_id;
    public static $api_domain_refresh_token;

    public function __construct($companyId)
    {
       self::$company_id = $companyId;
       $this->initialize();
    }

    public function initialize()
    {
        if(!isset(self::$access_token)){
            $generateAccessToken = $this->getAccessToken();
            self::$access_token = $generateAccessToken;
       }

       if(!isset(self::$api_domain)){
            $getApiDomain = $this->getApiDomain();
            self::$api_domain = $getApiDomain;
       }

       if(!isset(self::$api_domain_refresh_token)){
            $refreshTokenApiDomain = $this->refreshTokenApiDomain();
            self::$api_domain_refresh_token = $refreshTokenApiDomain;
       }
    }

    public function connect($params, $mode='production')
    {
        $params = json_decode($params, true);
        $client_id = $params['zoho']['client_id'];
        $client_secret = $params['zoho']['client_secret'];
        $call_back_uri = $params['zoho']['call_back_uri'];
        $companyId = self::$company_id;
        
        $redirect_location = 'https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.roles.ALL&client_id='.$client_id.'&response_type=code&access_type=offline&redirect_uri='.$call_back_uri;

        if(isset($mode)){
            if($mode === 'production'){
                $redirect_location = env('APP_URL').'/oauth2callback';
            }
        }

        session([
            'zoho_config' => [
                'company_id' => $companyId,
                'zoho_client_mode' => $mode,
                'zoho_client_id' => $client_id,
                'zoho_client_secret' => $client_secret,
                'zoho_call_back_uri' => $call_back_uri,
                'redirect_location' => $redirect_location
            ]
        ]);
        
        CrmAbstract::saveLog($redirect_location, 'AUTHENTICATION', $companyId, $params, 'GET', [], 'request', NULL, NULL, NULL, NULL);

        return response()->json(['redirect_location' => $redirect_location]);
    }
    
    public function oAuthCallback(Request $request)
    {
        $settings = [];
        $request_code = $request->get('code');
        $zohoConfigSettings = session('zoho_config');
        $mode = isset($zohoConfigSettings['zoho_client_mode']) ? $zohoConfigSettings['zoho_client_mode'] : null;
        $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=failed&zoho_oauth_mode='.$mode;

        $companyId = $zohoConfigSettings['company_id'];
        $client_id = isset($zohoConfigSettings['zoho_client_id']) ? $zohoConfigSettings['zoho_client_id'] : null;
        $client_secret = isset($zohoConfigSettings['zoho_client_secret']) ? $zohoConfigSettings['zoho_client_secret'] : null;
        $call_back_uri = isset($zohoConfigSettings['zoho_call_back_uri']) ? $zohoConfigSettings['zoho_call_back_uri'] : null;
       
        $code = isset($request_code) ? $request_code : null;

        if(isset($companyId) && isset($client_id) && isset($client_secret) && isset($call_back_uri) && isset($mode)){
            if($mode === 'production'){
                $authLogId = DB::table('request_logs')->latest('id')->first();
                if(isset($authLogId->id)){
                    CrmAbstract::$logId = $authLogId->id;
                    CrmAbstract::saveLog($zohoConfigSettings['redirect_location'], 'AUTHENTICATION', $companyId, [], 'GET', [], 'response', NULL, 200, 'Live Redirection url generated successfully', $zohoConfigSettings['redirect_location']);
                }

                $settings['crm_client_id'] = $client_id;
                $settings['crm_client_secret'] = $client_secret;
                $settings['crm_call_back_uri'] = $call_back_uri;
                $settings['crm_sync_items'] = 'Yes';
                $settings['crm_sync_users'] = 'Yes';
                $settings['crm_sync_roles'] = 'Yes';
                
                CrmAbstract::saveLog($location, 'REFRESH-TOKEN', $companyId, $zohoConfigSettings, 'POST', [], 'request', NULL, NULL, NULL, NULL);
                CompanySetting::setSettings($settings, $companyId);

                $saveOauthToken = $this->saveOauthToken('', $mode, $companyId);
                if($saveOauthToken){
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=success';
                   
                    CrmAbstract::saveLog($location, 'REFRESH-TOKEN', $companyId, $zohoConfigSettings, 'POST', [], 'response', NULL, 200, 'Live Token generation successfull', $zohoConfigSettings);
                    self::removeTestToken();
                }
                else{
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=failed';
                }
            }
            else if($mode === 'test' && isset($code)){
                $authLogId = DB::table('request_logs')->latest('id')->first();
                if(isset($authLogId->id)){
                    CrmAbstract::$logId = $authLogId->id;
                    CrmAbstract::saveLog($zohoConfigSettings['redirect_location'], 'AUTHENTICATION', $companyId, [], 'GET', [], 'response', NULL, 200, 'Test Redirection url generated successfully', $zohoConfigSettings['redirect_location']);
                }

                CrmAbstract::saveLog($request['accounts-server'].'/oauth/v2/token', 'REFRESH-TOKEN', $companyId, [ 
                'grant_type'=> 'authorization_code',
                'client_id' => $client_id,
                'client_secret' =>$client_secret,
                'redirect_uri' => $call_back_uri,
                'code' => $code
                ], 'POST', [], 'request', NULL, NULL, NULL, NULL);

                $responseCode = 500;
                $responseMessage = 'Token generation failed';

                $response = Http::asForm()->post($request['accounts-server'].'/oauth/v2/token', [
                    'grant_type'=> 'authorization_code',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $call_back_uri,
                    'code' => $code
                ]);

                $content = $response->body();
                $decodeContent = json_decode($content, true);
                if(is_array($decodeContent)){
                    if(isset($decodeContent['error'])){
                        $responseMessage = $decodeContent['error'];
                    }

                    if(isset($decodeContent['access_token'])){  
                        $responseCode = 200;
                        $responseMessage = 'Test token generation successfull';
                    }
                }
                else if($decodeContent == NULL){
                    $content = NULL;
                }

                $tokenLogId = CrmAbstract::$logId;
                if($tokenLogId){
                    CrmAbstract::saveLog($request['accounts-server'].'/oauth/v2/token', 'REFRESH-TOKEN', $companyId, [ 
                        'grant_type'=> 'authorization_code',
                        'client_id' => $client_id,
                        'client_secret' =>$client_secret,
                        'redirect_uri' => $call_back_uri,
                        'code' => $code
                    ], 'POST', [], 'response', NULL, $responseCode, $responseMessage, $content);
                }

                if(isset($decodeContent['access_token'])){
                    $decodeContent['refresh_token_url'] = $request['accounts-server']; 
                    $content = json_encode($decodeContent, true);
                    $saveOauthToken = $this->saveOauthToken($content, $mode, $companyId);
                    if($saveOauthToken){
                        $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_client_id='.$client_id.'&zoho_client_secret='.$client_secret.'&zoho_oauth_token_file=success';
                    }
                    else {
                        $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=failed';
                    }
                } 
            }
        }

        return redirect($location);
        exit;
    }

    public function saveOauthToken($content, $mode)
    {
        $companyId = self::$company_id;
        $return = false;
        if(isset($mode)){
            $tokenPath = base_path(). '/storage/tokens/refresh-token';
            if(!file_exists($tokenPath)){
                mkdir($tokenPath, 0755, true); 
            }
           
            if($mode === 'production'){
                $getFileContents = file_get_contents(base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.test');
                $writeFile = file_put_contents(base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.live', $getFileContents);
            }
            else if($mode === 'test'){
                $writeFile = file_put_contents(base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.test', $content);
            }

            if($writeFile == 332 || $writeFile == 383){
                $return = true;
            }
            
        }

        return $return;
    }

    public function generateRefreshToken(){
        $companyId = self::$company_id;
        $refreshTokenApiDomain = self::$api_domain_refresh_token;
        $return = false;
        $crmSettings = CompanySetting::getSettings(['crm_client_id', 'crm_client_secret'], $companyId)->toArray();
        if(isset($crmSettings['crm_client_id']) && isset($crmSettings['crm_client_secret'])){
            $client_id = $crmSettings['crm_client_id'];
            $client_secret = $crmSettings['crm_client_secret'];
            $getFileContents = file_get_contents(base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.live');
            $fileContents = json_decode($getFileContents, true); 

            if(is_array($fileContents)){
                if(isset($fileContents['refresh_token'])){
                    $refresh_token = $fileContents['refresh_token'];

                    CrmAbstract::saveLog($refreshTokenApiDomain.'/oauth/v2/token', 'ACCESS-TOKEN', $companyId, [ 
                        'refresh_token' => $refresh_token,
                        'grant_type'=> 'refresh_token',
                        'client_id' => $client_id,
                        'client_secret' => $client_secret
                    ], 'POST', [], 'request', NULL, NULL, NULL, NULL);

                    $responseCode = 500;
                    $responseMessage = 'Access token generation failed';

                    $response = Http::asForm()->post($refreshTokenApiDomain.'/oauth/v2/token', [
                                    'refresh_token' => $refresh_token,
                                    'grant_type'=> 'refresh_token',
                                    'client_id' => $client_id,
                                    'client_secret' => $client_secret
                                ]);
                    
                    $responseContent = $response->body();
                    $content = json_decode($responseContent, true);

                    if(isset($content['access_token'])){
                        $responseCode = 200;
                        $responseMessage = 'Access token generation successfull';
                    }

                    CrmAbstract::saveLog($refreshTokenApiDomain.'/oauth/v2/token', 'ACCESS-TOKEN', $companyId, [ 
                        'refresh_token' => $refresh_token,
                        'grant_type'=> 'refresh_token',
                        'client_id' => $client_id,
                        'client_secret' => $client_secret
                    ], 'POST', [], 'response', NULL, $responseCode, $responseMessage, $responseContent);

                    
                    if(is_array($content)){
                        if(isset($content['access_token'])){
                            $accessTokenPath = base_path(). '/storage/tokens/access-token';
                            if(!file_exists($accessTokenPath)){
                                mkdir($accessTokenPath, 0755, true); 
                            }

                            $writeFile = file_put_contents(base_path().'/storage/tokens/access-token/zoho_token_'.$companyId.'.live', $responseContent);
                            if($writeFile == 167 || $writeFile == 243){
                                $return = true;
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    public function syncProducts(){
        $response =  [
            'response' => false,
            'message' => 'Items sync failed.'
        ];

        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        $companyId = self::$company_id;
        $company = Company::where('id', $companyId)->first();
        
        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $lastBatchUploadedId = (int)BatchUpload::max('id');
            $batchUpload = BatchUpload::create([
                'company_id' => $company->id,
                'name' => 'batch_no_'.$lastBatchUploadedId+1,
                'type' => 'API',
                'status' => 'uploaded',
                'model' => 'ITEMS',
                'created_by' => 1
            ]);

            $url = $api_domain.''."/crm/v2/Products?";

            $page = 1;
            $per_page = 20;
            $more_records = true;

            while($more_records){
                $more_records = false;
                $parameters = [];
                $parameters["per_page"] = $per_page;
                $parameters["page"] = $page;

                $products = $this->prepareCurlRequest($url, $access_token, "ITEMS", $companyId, $parameters, "GET", []);

                if(isset($products['data'])){
                    if(count($products['data']) > 0){
                        $lastRequestLogId = (int)RequestLog::max('id');

                        $units = $company->units()->pluck('id')->toArray();
                        $companyCurrencyId = CompanySetting::getSetting('currency', $company->id);
                        $companyCurrency = Currency::where('id', $companyCurrencyId)->first();
    
                        $mappedColumns = $this->mappedCompanyFields($company->id, 'items');
    
                        foreach($products['data'] as $product){
                            $eachItem = [];
    
                            foreach($mappedColumns as $key => $value){
                                $childKeys = $this->childKeySearch($key);
                                if(count($childKeys) > 0){
                                   if(isset($value) && isset($product[$childKeys[0]])){
                                        if(isset($product[$childKeys[0]][$childKeys[1]])){
                                            $eachItem[$value] = $product[$childKeys[0]][$childKeys[1]];
                                        }
                                    }
                                }
                                else{
                                    if(isset($value) && isset($product[$key])){
                                        $eachItem[$value] = $product[$key];
                                    }
                                }
                            }
                            
                            if(count($eachItem) > 0){
                                $eachItem['company_id'] = $company->id;
                                $eachItem['creator_id'] = 1;
                                $eachItem['unit_id'] = isset($units[0]) ? $units[0] : NULL;
                                $eachItem['currency_id'] = $companyCurrency->id;
                                $eachItem['currency_symbol'] = $companyCurrency->symbol;
                                
                                BatchUploadRecord::create([
                                    'batch_id' => $batchUpload->id,
                                    'row_data' => json_encode($eachItem),
                                    'request_log_id' => $lastRequestLogId
                                ]);
                            }
                        }

                        if(isset($products['info']['more_records'])){
                            if($products['info']['more_records'] == true){
                                $more_records = true;
                                $page++;
                            }
                            else{
                                $response = [
                                    'response' => true,
                                    'message' => 'Items sync is successfull.'
                                ];
                            }
                        }
                    }
                }else{
                
                    $response = [
                        'response' => false,
                        'message' => 'Unable to retrieve products from CRM due to '.$products['message'].'.'
                    ];
                }
            }
        }
        else{
        
            $response = [
                'response' => false,
                'message' => 'The generation of the access token failed.'
            ];
        }

        return $response;
    }

    public function fetchCrmProducts(){
        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        $companyId = self::$company_id;

        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $url = $api_domain.''."/crm/v2/Products?";

            $parameters = [];
            $parameters["per_page"] = 1;
            $parameters["page"] = 1;

            $crmProducts = $this->prepareCurlRequest($url, $access_token, "ITEMS", $companyId, $parameters, "GET", []);
            if(isset($crmProducts['data'])){
                $products['data'] = [];
                
                foreach($crmProducts['data'] as $eachCrmProduct){
                    foreach($eachCrmProduct as $key => $value){
                        if($key === 'Owner' || $key === 'Vendor_Name' || $key === 'Created_By' || $key === 'Modified_By'){
                          
                            $addFieldsWithKeys = $this->breakField($key, $value);
                            foreach($addFieldsWithKeys as $fieldKey => $fieldValue){
                                $eachCrmProduct[$fieldKey] = $fieldValue;
                            }
                           
                            unset($eachCrmProduct[$key]);
                        }
                       
                        $hasSpecialChar = $this->checkSpecialCharacters($key);
                        if($hasSpecialChar){
                        
                            unset($eachCrmProduct[$key]);
                        }
                    }
                    
                    $products['data'][] = array_merge($eachCrmProduct);
                }

                return response()->json(['crm_products' => $products], 200);
            }
            else if(isset($crmProducts['code'])){
                
                return $crmProducts;
            }
           
        }
    }

    public function getAccessToken(){
        $companyId = self::$company_id;
        $return = '';
        $filename = base_path().'/storage/tokens/access-token/zoho_token_'.$companyId.'.live';
        if(file_exists($filename)){
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
    
            $data =  json_decode($contents, true);
            $return = $data['access_token'];
        }

        return $return;
    }

    public function getApiDomain(){
        $companyId = self::$company_id;
        $return = '';
        $filename = base_path().'/storage/tokens/access-token/zoho_token_'.$companyId.'.live';
        if(file_exists($filename)){
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
    
            $data =  json_decode($contents, true);
            $return = $data['api_domain'];
        }

        return $return;
    }

    public function refreshTokenApiDomain(){
        $companyId = self::$company_id;
        $return = '';
        $filename = base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.live';
        if(file_exists($filename)){
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
    
            $data =  json_decode($contents, true);
            $return = $data['refresh_token_url'];
        }

        return $return;
    }

    public function syncUsers(){
        $response =  [
            'response' => false,
            'message' => 'Users sync failed.'
        ];

        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        $companyId = self::$company_id;
        $company = Company::where('id', $companyId)->first();
        
        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $lastBatchUploadedId = (int)BatchUpload::max('id');
            $batchUpload = BatchUpload::create([
                'company_id' => $company->id,
                'name' => 'batch_no_'.$lastBatchUploadedId+1,
                'type' => 'API',
                'status' => 'uploaded',
                'model' => 'USERS',
                'created_by' => 1
            ]);

            $url = $api_domain.''."/crm/v2/users?";

            $page = 1;
            $per_page = 20;
            $type = "ActiveUsers";
            $more_records = true;

            while($more_records){
                $more_records = false;
                $parameters = [];
                $parameters["type"] = $type;
                $parameters["per_page"] = $per_page;
                $parameters["page"] = $page;

                $users = $this->prepareCurlRequest($url, $access_token, "USERS", $companyId, $parameters, "GET", []);

                if(isset($users['users'])){
                    if(count($users['users']) > 0){
                        $lastRequestLogId = (int)RequestLog::max('id');

                        $companyCurrencyId = CompanySetting::getSetting('currency', $company->id);
                        $companyCurrency = Currency::where('id', $companyCurrencyId)->first();
    
                        $mappedColumns = $this->mappedCompanyFields($company->id, 'users');
    
                        foreach($users['users'] as $user){
                            $eachUser = [];
    
                            foreach($mappedColumns as $key => $value){
                                $childKeys = $this->childKeySearch($key);
                                if(count($childKeys) > 0){
                                   if(isset($value) && isset($user[$childKeys[0]])){
                                        if(isset($user[$childKeys[0]][$childKeys[1]])){
                                            $eachUser[$value] = $user[$childKeys[0]][$childKeys[1]];
                                        }
                                    }
                                }
                                else{
                                    if(isset($value) && isset($user[$key])){
                                        $eachUser[$value] = $user[$key];
                                    }
                                }
                            }
                            
                            if(count($eachUser) > 0){
                                $eachUser['company_id'] = $company->id;
                                $eachUser['creator_id'] = 1;
                                $eachUser['currency_id'] = $companyCurrency->id;

                                BatchUploadRecord::create([
                                    'batch_id' => $batchUpload->id,
                                    'row_data' => json_encode($eachUser),
                                    'request_log_id' => $lastRequestLogId
                                ]);
                            }
                        }

                        if(isset($users['info']['more_records'])){
                            if($users['info']['more_records'] == true){
                                $more_records = true;
                                $page++;
                            }
                            else{
                                $response = [
                                    'response' => true,
                                    'message' => 'Users sync is successfull.'
                                ];
                            }
                        }
                    }
                }else{
                    $response = [
                        'response' => false,
                        'message' => 'Unable to retrieve users from CRM due to '.$users['message']
                    ];
                }
            }
        }
        else{
            $response = [
                'response' => false,
                'message' => 'The generation of the access token failed.'
            ];
        }

        return $response;
    }

    public function syncRoles(){
        $response =  [
            'response' => false,
            'message' => 'Roles sync failed.'
        ];

        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        $companyId = self::$company_id;
        $company = Company::where('id', $companyId)->first();
        
        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $lastBatchUploadedId = (int)BatchUpload::max('id');
            $batchUpload = BatchUpload::create([
                'company_id' => $company->id,
                'name' => 'batch_no_'.$lastBatchUploadedId+1,
                'type' => 'API',
                'status' => 'uploaded',
                'model' => 'CRM_ROLES',
                'created_by' => 1
            ]);

            $url = $api_domain.''."/crm/v2/settings/roles?";
            $parameters = [];

            $crmRoles = $this->prepareCurlRequest($url, $access_token, "CRM_ROLES", $companyId, $parameters, "GET", []);

            if(isset($crmRoles['roles'])){
                if(count($crmRoles['roles']) > 0){
                    $lastRequestLogId = (int)RequestLog::max('id');

                    foreach($crmRoles['roles'] as $role){
                        $eachRole = [];
                        $eachRole['role_id'] = $role['id'];
                        $eachRole['role_name'] = $role['name'];
                        $eachRole['reporting_manager_crm'] = NULL;
                        if(isset($role['reporting_to']['id'])){
                            $eachRole['reporting_manager_crm'] = $role['reporting_to']['id'];
                        }
                        $eachRole['created_by'] = 1;
                        $eachRole['updated_by'] = 1;

                        BatchUploadRecord::create([
                            'batch_id' => $batchUpload->id,
                            'row_data' => json_encode($eachRole),
                            'request_log_id' => $lastRequestLogId
                        ]);
                    }

                    $response = [
                        'response' => true,
                        'message' => 'Roles sync is successfull.'
                    ];
                }
            }
            else{
                $response = [
                    'response' => false,
                    'message' => 'Unable to retrieve roles from CRM due to '.$crmRoles['message']
                ];
            }

        }
        else{
            $response = [
                'response' => false,
                'message' => 'The generation of the access token failed.'
            ];
        }

        return $response;
    }

    public function childKeySearch($string){
        $childKeyArray = [];
        if(preg_match('/\->/', $string)){
           
            $keyArray = explode('->', $string);
            foreach($keyArray as $key => $value){
                $childKeyArray[$key] = strtolower($value);
            }
        }

        return $childKeyArray;
    }

    public function fetchCrmUsers(){
        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        $companyId = self::$company_id;

        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $url = $api_domain.''."/crm/v2/users?";

            $parameters = [];
            $parameters["type"] = "AllUsers";
            $parameters["per_page"] = 1;
            $parameters["page"] = 1;
        
            $crmUsers = $this->prepareCurlRequest($url, $access_token, "USERS", $companyId, $parameters, "GET", []);
            if(isset($crmUsers['users'])){
                $crm_users['data'] = [];

                foreach($crmUsers['users'] as $eachCrmUser){
                    foreach($eachCrmUser as $key => $value){
                        if(in_array($key, [
                            'Owner', 
                            'Vendor_Name', 
                            'Created_By', 
                            'created_by', 
                            'Modified_By', 
                            'role',
                            'profile',
                            'territories'
                            ])){
                            
                            $addFieldsWithKeys = $this->breakField($key, $value);
                           
                            foreach($addFieldsWithKeys as $fieldKey => $fieldValue){
                                $eachCrmUser[$fieldKey] = $fieldValue;
                            }
                           
                            unset($eachCrmUser[$key]);
                        }
                        
                        $hasSpecialChar = $this->checkSpecialCharacters($key);
                        if($hasSpecialChar){
                          
                            unset($eachCrmUser[$key]);
                        }
                    }
                    
                    $crm_users['data'][] = array_merge($eachCrmUser);
                }
               
                return response()->json(['crm_users' => $crm_users], 200);
            }
            else if(isset($crmUsers['code'])){
                
                return $crmUsers;
            }
        }
    }

    public function prepareCurlRequest($url, $access_token, $type, $companyId, $parameters, $method, $headersArray){
        
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
        $result = $this->curlRequest($url, $type, $companyId, $parameters, $method, $headersArray, true);
        $resultResponse = $this->parseCurlResponse($result);

        $logId = CrmAbstract::$logId;
        if($logId){
            $responseCode = isset($resultResponse['code']) ? $resultResponse['code'] : NULL;
            $responseMessage = isset($resultResponse['message']) ? $resultResponse['message'] : NULL;
            $responseBody = isset($resultResponse['body']) ? $resultResponse['body'] : NULL;
            
            if(isset($resultResponse['users'])){
                $responseCode = 200;
                $responseMessage = 'Request processed successfully.';
                $responseBody = $resultResponse;
            }
            
            CrmAbstract::saveLog($url, $type, $companyId, $parameters, $method, $headersArray, 'response', NULL, $responseCode, $responseMessage, $responseBody);
        }

        return $resultResponse;
    }

    public function parseCurlResponse($result)
    {
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }

        $jsonResponse = json_decode($content, true);
        return $jsonResponse;
    }

    public static function removeTestToken(){
        $companyId = self::$company_id;
        $return = false;
        $filename = base_path().'/storage/tokens/refresh-token/zoho_token_'.$companyId.'.test';
        if(file_exists($filename)){
            $removeFile = unlink($filename);
            if($removeFile){
                $return = true;
            }
        }

        return $return;
    }

    public function breakField($eachKey, $values){
        $fieldArray = [];
        foreach($values as $key => $value){
            $fieldArray[$eachKey.'->'.ucfirst($key)] = $value;
        }
        return $fieldArray;
    }

    public function checkSpecialCharacters($string) {
        if(preg_match('/\$/', $string)){
            return true;
        }
        return false;
    }

    public function mappedCompanyFields($companyId, $tableName){

        $mappedFieldColumns = [];
        $mappedCompanyFields = CompanyField::where('company_id', $companyId)
                            ->where('table_name', $tableName)
                            ->where('crm_mapped_field', '<>', '')
                            ->get();
        
        if(isset($mappedCompanyFields)){
            foreach($mappedCompanyFields as $eachMappedField){
                $mappedFieldColumns[$eachMappedField->crm_mapped_field] = $eachMappedField->column_name;
            }
        }

        return $mappedFieldColumns;
    }

    public function companyFieldMapping($apiFields, $tableName){
        $companyId = self::$company_id;
        if(count($apiFields) > 0){
            foreach($apiFields as $eachApiField){
                if(isset($eachApiField['column_name'])){
                    CompanyField::where('column_name', $eachApiField['column_name'])
                        ->where('company_id', $companyId)
                        ->update([
                            'crm_mapped_field' => $eachApiField['api_key']
                        ]);
                    
                    if(isset($eachApiField['is_crm_standard_mapping'])){
                        if($eachApiField['is_crm_standard_mapping']){
                            $existStandardMapping = CrmStandardMapping::where('crm_name', 'zoho')
                                ->where('table_name', $tableName)
                                ->where('field_name', $eachApiField['column_name'])
                                ->where('crm_column_name', $eachApiField['api_key'])
                                ->first();

                            if(isset($existStandardMapping)){
                                $existStandardMapping->crm_name = 'zoho';
                                $existStandardMapping->table_name = $tableName;
                                $existStandardMapping->field_name = $eachApiField['column_name'];
                                $existStandardMapping->crm_column_name = $eachApiField['api_key'];
                                $existStandardMapping->status = 'active';
                                $existStandardMapping->update();
                            }
                            else{
                                $addStandardMapping = new CrmStandardMapping();
                                $addStandardMapping->crm_name = 'zoho';
                                $addStandardMapping->table_name = $tableName;
                                $addStandardMapping->field_name = $eachApiField['column_name'];
                                $addStandardMapping->crm_column_name = $eachApiField['api_key'];
                                $addStandardMapping->status = 'active';
                                $addStandardMapping->save();
                            }
                        }
                    }
                }
            }
        }

        return ['success' => 'Company Fields Mapping updated.'];
    }

    public function fetchTableColumns($tableName){
        $response = [];
        $companyId = self::$company_id;
        $user = request()->user();
        
        $roleId = DB::table('assigned_roles')
                    ->where('entity_id', $user->id)
                    ->where('scope', request()->header('company'))
                    ->value('role_id');

        $roleName = DB::table('roles')
                        ->where('id', $roleId)
                        ->value('name');

        $tableData = CompanyField::select([
                        'id', 
                        'column_name', 
                        'column_type', 
                        'field_type',
                        'is_system',
                        'visiblity', 
                        'crm_mapped_field'
                    ])
                    ->where('table_name', $tableName)->where('company_id', $companyId)
                    ->get()->toArray();

        if(count($tableData) > 0){
            $table_data = [];
            foreach($tableData as $eachTableData){
                $eachTableData['is_crm_standard_mapping'] = false;
                if(isset($eachTableData['column_name']) && isset($eachTableData['crm_mapped_field'])){
                    $existCrmStandardMapping = CrmStandardMapping::where('crm_name', 'zoho')
                                                    ->where('table_name', $tableName)
                                                    ->where('field_name', $eachTableData['column_name'])
                                                    ->where('crm_column_name', $eachTableData['crm_mapped_field'])
                                                    ->first();

                    if(isset($existCrmStandardMapping)){
                        $eachTableData['is_crm_standard_mapping'] = true;
                    }
                }

                if($roleName === 'super admin'){
                    $table_data[] = $eachTableData;
                }

                if($eachTableData['visiblity'] !== 'locked'){
                    if($roleName == 'admin'){
                        if( in_array($eachTableData['visiblity'], ['visible'])){
                            $table_data[] = $eachTableData;
                        }
                    }
                    else if($roleName !== 'admin' && $roleName !== 'super admin'){
                        if($eachTableData['is_system'] === 'no' && $eachTableData['visiblity'] === 'visible'){
                            $table_data[] = $eachTableData;
                        }
                    }
                }
            }
            
            $response =  response()->json(['table_columns' => $table_data]);
        }
       
        return $response;
    }
}
