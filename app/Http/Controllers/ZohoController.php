<?php

namespace Crater\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Crater\Models\ZohoRecord;
use Crater\Models\ZohoToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ZohoController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function intialize(){

        $zohoRecords = new ZohoRecord();
        $zohoRecords->initialize();
    }

    public function testFile(){
	    echo base_path();
$fp = fopen(base_path().'/storage/data.txt', 'w');
fwrite($fp, '1');
fwrite($fp, '234');
fclose($fp);

	    die("test file");
    }
    public function oAuth(){
        $client_id = env('ZOHO_CLIENT_ID');
        $client_secret = env('ZOHO_CLIENT_SECRET');
        $call_back_uri = env('APP_URL')."/".env('ZOHO_CALLBACK_URI');
        $location = 'https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL&client_id='.$client_id.'&response_type=code&access_type=offline&redirect_uri='.$call_back_uri;
        return redirect($location);
    }

    public function oAuthCallback(Request $request){
        $code = $request->get('code');
        $client_id = env('ZOHO_CLIENT_ID');
        $client_secret = env('ZOHO_CLIENT_SECRET');
        $call_back_uri = env('APP_URL')."/".env('ZOHO_CALLBACK_URI');

        $response = Http::asForm()->post('https://accounts.zoho.in/oauth/v2/token', [
            'grant_type'=> 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $call_back_uri,
            'code' => $code
        ]);

        $content = $response->body();

        //Storage::disk('local')->put('oauth_token.txt', $content, 'public');

	$fp = fopen(base_path().'/storage/oauth_token.txt', 'w');
	fwrite($fp, $content);
	fclose($fp);

        $zohoToken = new ZohoToken();

        $save_token = $zohoToken->saveAccessToken($content);
        
        $location = env('APP_URL').'/admin/items?zoho_auth=success';
        return redirect($location);
        exit;
    }

    public function generateRefreshToken(ZohoToken $zohoToken){

        $client_id = env('ZOHO_CLIENT_ID');
        $client_secret = env('ZOHO_CLIENT_SECRET');
        $call_back_uri = env('APP_URL')."/".env('ZOHO_CALLBACK_URI');

        $refresh_token = $zohoToken->getRefreshToken(); 

        $response = Http::asForm()->post('https://accounts.zoho.in/oauth/v2/token', [
            'refresh_token' => $refresh_token,
            'grant_type'=> 'refresh_token',
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ]);

        $content = $response->body();

        $save_token = $zohoToken->saveAccessToken($content);

    }
}
