<?php

namespace Crater\Http\Controllers;

use Crater\Models\Currency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Crater\Models\Item;
use Crater\Models\Unit;
use Crater\Models\ZohoRecord;
use Crater\Models\ZohoToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Null_;

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

    public function syncProducts(){
        $jsonResponse = $this->getZohoProducts();
        $zohoProducts = $jsonResponse['data'];
        
        foreach($zohoProducts as $zohoProduct){

            $item = Item::where('item_code', $zohoProduct['Product_Code'])->first();

            if(!isset($item)){

                $item = new Item();
                $item->is_sync = false;
                $item->name = $zohoProduct['Product_Name'];
                $item->description = $zohoProduct['Description'];
                if(isset($zohoProduct['Unit_Price'])){
                    $zohoProduct['Unit_Price'] = number_format($zohoProduct['Unit_Price'], 2, '.', '');
                    $item->price = $zohoProduct['Unit_Price'];
                }
                $item->company_id = 1;

                $unit = Unit::where('name', 'unit')->first();
                if(isset($unit)){
                    $item->unit_id = $unit->id;
                }

                if(isset($zohoProduct['Tax'][0])){
                    $item->tax_per_item = $zohoProduct['Tax'][0];
                }

                $item->creator_id = $zohoProduct['Created_By']['id'];
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
                $item->save();

            }else{
                $item->is_sync = false;
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
                $item->creator_id = $zohoProduct['Created_By']['id'];
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
                $item->update();

            }
        }
    }

    public function zohoProducts(){
       $jsonResponse = $this->getZohoProducts();
       echo "<pre>";
       print_r($jsonResponse['data']);
    }

    public function getZohoProducts(){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        $url = "https://www.zohoapis.in/crm/v2/Products?";
        $parameters = array();
        $parameters["sort_by"]="Email";
        $parameters["sort_order"]="desc";
        $parameters["include_child"]="true";

        // get contents of a file into a string
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

        foreach ($parameters as $key=>$value){
            $url = $url.$key."=".$value."&";
        }

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
        $headersArray = array();
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
        $headersArray[] = "If-Modified-Since".":"."2023-08-12T17:59:50+05:30";
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
        curl_setopt_array($curl_pointer, $curl_options);
        
        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
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
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }

        return $jsonResponse;
        
    }

    public function getLeads(){

        $curl_pointer = curl_init();
        
        $curl_options = array();
        $url = "https://www.zohoapis.in/crm/v2/Leads?";
        $parameters = array();
        $parameters["sort_by"]="Email";
        $parameters["sort_order"]="desc";
        $parameters["include_child"]="true";

        // get contents of a file into a string
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

        foreach ($parameters as $key=>$value){
            $url = $url.$key."=".$value."&";
        }

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
        $headersArray = array();
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
        $headersArray[] = "If-Modified-Since".":"."2023-08-12T17:59:50+05:30";
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
        curl_setopt_array($curl_pointer, $curl_options);
        
        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
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
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }

        return $jsonResponse;
        
    }

    public function getZohoLead($id){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        $url = "https://www.zohoapis.in/crm/v2/Leads/".$id;
        $parameters = array();

        // get contents of a file into a string
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

        foreach ($parameters as $key=>$value){
            $url = $url.$key."=".$value."&";
        }

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
        $headersArray = array();
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
        $headersArray[] = "If-Modified-Since".":"."2023-08-12T17:59:50+05:30";
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
        curl_setopt_array($curl_pointer, $curl_options);
        
        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
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
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }

        // return $jsonResponse;

       echo "<pre>";
       print_r($jsonResponse['data']);
    }
}
