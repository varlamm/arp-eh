<?php
namespace Xcelerate\Models\Crm\Providers\Zoho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Xcelerate\Models\Company;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\ZohoToken;

class ZohoCrm
{
    public function __construct()
    {
       
    }

    public function connect($params, $companyId, $mode='production')
    {
        $companyId;
        $params = json_decode($params, true);
        $client_id = $params['zoho']['client_id'];
        $client_secret = $params['zoho']['client_secret'];
        $call_back_uri = $params['zoho']['call_back_uri'];

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

    public function saveOauthToken($content, $mode, $companyId)
    {
        $return = false;
        if(isset($mode)){
            if($mode === 'production'){
                $getFileContents = file_get_contents(base_path().'/storage/zoho_oauth_token_company_'.$companyId.'.test.txt');
                $writeFile = file_put_contents(base_path().'/storage/zoho_oauth_token_company_'.$companyId.'.live.txt', $getFileContents);
            }
            else if($mode === 'test'){
                $writeFile = file_put_contents(base_path().'/storage/zoho_oauth_token_company_'.$companyId.'.test.txt', $content);
            }

            if($writeFile == 332){
                $return = true;
            }
        }

        return $return;
    }
}
