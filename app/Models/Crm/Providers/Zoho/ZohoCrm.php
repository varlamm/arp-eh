<?php
namespace Xcelerate\Models\Crm\Providers\Zoho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Xcelerate\Models\Company;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\Crm\Providers\CrmAbstract;
use Xcelerate\Models\Currency;
use Xcelerate\Models\Item;
use Xcelerate\Models\Unit;
use Xcelerate\Models\ZohoToken;

class ZohoCrm extends CrmAbstract
{
    public static $access_token;
    public static $api_domain;
    public static $company_id;

    public function __construct($companyId)
    {
       self::$company_id = $companyId;
       $this->initialize();
    }

    public function initialize()
    {
        if(!isset(self::$access_token)){
            $generateAccessToken = $this->getAccessToken(self::$company_id);
            self::$access_token = $generateAccessToken;
       }

       if(!isset(self::$api_domain)){
            $getApiDomain = $this->getApiDomain(self::$company_id);
            self::$api_domain = $getApiDomain;
       }
    }

    public function connect($params, $mode='production')
    {
        $params = json_decode($params, true);
        $client_id = $params['zoho']['client_id'];
        $client_secret = $params['zoho']['client_secret'];
        $call_back_uri = $params['zoho']['call_back_uri'];
        $companyId = self::$company_id;
        
        session([
            'zoho_config' => [
                'company_id' => $companyId,
                'zoho_client_mode' => $mode,
                'zoho_client_id' => $client_id,
                'zoho_client_secret' => $client_secret,
                'zoho_call_back_uri' => $call_back_uri
            ]
        ]);
        
        $redirect_location = 'https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.roles.ALL&client_id='.$client_id.'&response_type=code&access_type=offline&redirect_uri='.$call_back_uri;

        if(isset($mode)){
            if($mode === 'production'){
                $redirect_location = env('APP_URL').'/oauth2callback';
            }
        }

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
                $settings['crm_client_id'] = $client_id;
                $settings['crm_client_secret'] = $client_secret;
                $settings['crm_call_back_uri'] = $call_back_uri;

                CompanySetting::setSettings($settings, $companyId);

                $saveOauthToken = $this->saveOauthToken('', $mode, $companyId);
                if($saveOauthToken){
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=success';
                }
                else{
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=failed';
                }
            }
            else if($mode === 'test' && isset($code)){
                $response = Http::asForm()->post('https://accounts.zoho.in/oauth/v2/token', [
                    'grant_type'=> 'authorization_code',
                    'client_id' => $client_id,
                    'client_secret' =>$client_secret,
                    'redirect_uri' => $call_back_uri,
                    'code' => $code
                ]);
                $content = $response->body();
    
                $saveOauthToken = $this->saveOauthToken($content, $mode, $companyId);

                if($saveOauthToken){
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_client_id='.$client_id.'&zoho_client_secret='.$client_secret.'&zoho_oauth_token_file=success';
                }
                else {
                    $location = env('APP_URL').'/admin/settings/crm-config?zoho_oauth_msz=success&zoho_oauth_mode='.$mode.'&zoho_oauth_token_file=failed';
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

            if($writeFile == 332){
                $return = true;
            }
            
        }

        return $return;
    }

    public function generateRefreshToken(){
        $companyId = self::$company_id;
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
                    $response = Http::asForm()->post('https://accounts.zoho.in/oauth/v2/token', [
                                    'refresh_token' => $refresh_token,
                                    'grant_type'=> 'refresh_token',
                                    'client_id' => $client_id,
                                    'client_secret' => $client_secret
                                ]);
                    
                    $responseContent = $response->body();
                    $content = json_decode($responseContent, true);
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
        $return =  [
            'response' => false,
            'message' => 'Items sync failed.'
        ];

        $access_token = self::$access_token;
        $api_domain = self::$api_domain;
        if(!empty($access_token) || $access_token !== '' && (!empty($api_domain) || $api_domain !== '')){
            $products = $this->getProducts();
            if(isset($products['data'])){
                $zohoProducts = $products['data'];
                if(count($zohoProducts) > 0){
                    Item::where('id', '>', 0)->update([
                        'is_deleted' => '1',
                        'is_sync' => false
                    ]);

                    foreach($zohoProducts as $zohoProduct){
    
                        $item = Item::where('item_code', $zohoProduct['Product_Code'])->first();
                        
                        if(!isset($item)){
                            $item = new Item();
                            $item->name = $zohoProduct['Product_Name'];
                            $item->description = $zohoProduct['Description'];
                            if(isset($zohoProduct['Unit_Price'])){
                                $zohoProductPrice = $zohoProduct['Unit_Price'];
                                $zohoProductPrice .= '00';
                                $item->price = $zohoProductPrice;
                            }
                            $item->company_id = 1;
            
                            $unit = Unit::where('name', 'unit')->first();
                            if(isset($unit)){
                                $item->unit_id = $unit->id;
                            }
            
                            if(isset($zohoProduct['Tax'][0])){
                                $item->tax_per_item = $zohoProduct['Tax'][0];
                            }
            
                            $item->creator_id = 1;
                            $item->currency_symbol = $zohoProduct['$currency_symbol'];
            
                            if(isset($zohoProduct['$currency_symbol'])){
                                $currency = Currency::where('symbol', 'like', '%'.$zohoProduct['$currency_symbol'].'%')->first();
                                if(isset($currency)){
                                    $item->currency_id = $currency->id;
                                }
                            }
                            
                            if(isset($zohoProduct['Price_AED'])){
                                $item->price_aed = $zohoProduct['Price_AED'];
                            }
            
                            if(isset($zohoProduct['Price_SAARC'])){
                                $item->price_saarc = $zohoProduct['Price_SAARC'];
                            }
            
                            if(isset($zohoProduct['Price_NAmerica_Europe'])){
                                $item->price_us = $zohoProduct['Price_NAmerica_Europe'];
                            }
                            
                            if(isset($zohoProduct['Price_ROW'])){
                                $item->price_row = $zohoProduct['Price_ROW'];
                            }
                            
                            $item->zoho_crm_id = $zohoProduct['id'];
                            $item->sync_date_time = date("Y-m-d H:i:s");
                            $item->item_code = $zohoProduct['Product_Code'];
                            $item->created_time = $zohoProduct['Created_Time'];
                            $item->updated_time = $zohoProduct['Modified_Time'];
                            $item->is_sync = true;
                            $item->is_deleted = '0';
                            $item->save();
                            
                        }else{
                           
                            $item->name = $zohoProduct['Product_Name'];
                            $item->description = $zohoProduct['Description'];
                            if(isset($zohoProduct['Unit_Price'])){
                                $zohoProductPrice = $zohoProduct['Unit_Price'];
                                $zohoProductPrice .= '00';
                                $item->price = $zohoProductPrice;
                            }
                            $item->company_id = 1;
            
                            $unit = Unit::where('name', 'unit')->first();
                            if(isset($unit)){
                                $item->unit_id = $unit->id;
                            }
            
                            if(isset($zohoProduct['Tax'][0])){
                                $item->tax_per_item = $zohoProduct['Tax'][0];
                            }
            
                            $item->creator_id = 1;
                            $item->currency_symbol = $zohoProduct['$currency_symbol'];
            
                            if(isset($zohoProduct['$currency_symbol'])){
                                $currency = Currency::where('symbol', 'like', '%'.$zohoProduct['$currency_symbol'].'%')->first();
                                if(isset($currency)){
                                    $item->currency_id = $currency->id;
                                }
                            }
                            
                            if(isset($zohoProduct['Price_AED'])){
                                $item->price_aed = $zohoProduct['Price_AED'];
                            }
            
                            if(isset($zohoProduct['Price_SAARC'])){
                                $item->price_saarc = $zohoProduct['Price_SAARC'];
                            }
            
                            if(isset($zohoProduct['Price_NAmerica_Europe'])){
                                $item->price_us = $zohoProduct['Price_NAmerica_Europe'];
                            }
                            
                            if(isset($zohoProduct['Price_ROW'])){
                                $item->price_row = $zohoProduct['Price_ROW'];
                            }
                            
                            $item->zoho_crm_id = $zohoProduct['id'];
                            $item->sync_date_time = date("Y-m-d H:i:s");
                            $item->item_code = $zohoProduct['Product_Code'];
                            $item->created_time = $zohoProduct['Created_Time'];
                            $item->updated_time = $zohoProduct['Modified_Time'];
                            $item->is_sync = true;
                            $item->is_deleted = '0';
                            $item->update();
                        }
                    }

                    $return = [
                        'response' => true,
                        'message' => 'Items synced successfully.'
                    ];
                }
            }
            else{
                $return = [
                    'response' => false,
                    'message' => 'Could not fetch products from Zoho CRM.'
                ];
            }
        }
        else{
            $return = [
                'response' => false,
                'message' => 'Access Token could not be generated.'
            ];
        }

        return $return;
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

    public function getProducts(){
        $companyId = self::$company_id;
        $access_token = self::$access_token;
        $apiDomain = self::$api_domain;

        $url = $apiDomain.''."/crm/v2/Products?";
        $parameters = [];
        $parameters["sort_by"]="Email";
        $parameters["sort_order"]="desc";
        $parameters["include_child"]="true";
        $method="GET";
        $headersArray = [];
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
        $result = $this->curlRequest($url, 'ITEMS', $companyId, $parameters, $method, $headersArray, true);
        $resultResponse = $this->parseCurlResponse($result);

        $logId = CrmAbstract::$logId;
        if($logId){
            $responseCode = isset($resultResponse['code']) ? $resultResponse['code'] : NULL;
            $responseMessage = isset($resultResponse['message']) ? $resultResponse['message'] : NULL;
            $responseBody = isset($resultResponse['body']) ? $resultResponse['body'] : NULL;
            
            if(isset($resultResponse['data'])){
                $responseCode = 200;
                $responseMessage = 'Request processed successfully.';
                $responseBody = $resultResponse;
            }
            
            CrmAbstract::saveLog($url, 'ITEMS', $companyId, $parameters, $method, $headersArray, 'response', NULL, $responseCode, $responseMessage, $responseBody);
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

}
