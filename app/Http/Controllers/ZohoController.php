<?php

namespace Xcelerate\Http\Controllers;

use Xcelerate\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Routing\Controller as BaseController;
use Xcelerate\Models\Item;
use Xcelerate\Models\Unit;
use Xcelerate\Models\ZohoRecord;
use Xcelerate\Models\ZohoToken;
use Xcelerate\Models\Customer;
use Xcelerate\Models\Currency;
use Xcelerate\Models\Invoice;
use Xcelerate\Models\InvoiceItem;
use Xcelerate\Models\TaxType;
use Xcelerate\Models\User;
use Xcelerate\Models\ZohoRole;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class ZohoController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use HasRolesAndAbilities;

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
        $location = 'https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.roles.ALL&client_id='.$client_id.'&response_type=code&access_type=offline&redirect_uri='.$call_back_uri;
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
        if(isset($jsonResponse['data'])){
            $zohoProducts = $jsonResponse['data'];
            if(count($zohoProducts) > 0){
                Item::where('id', '>', 0)->update([
                    'is_deleted' => '1',
                    'is_sync' => false
                ]);
            }
            foreach($zohoProducts as $zohoProduct){
    
                $item = Item::where('item_code', $zohoProduct['Product_Code'])->first();
                
                if(!isset($item)){
                    $item = new Item();
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
            return true;
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

    public function addZohoLead(){
        $apiUrl = "https://www.zohoapis.in/crm/v2/Leads";
        // get contents of a file into a string
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $token =  json_decode($contents, true);
        $accessToken = $token['access_token'];

        $data = [
            "data" => [
                [
                    "Layout" => [
                        "id" => "444231000000000167"
                    ],
                    "Lead_Source" => "Employee Referral",
                    "Company" => "Aster Health Academy",
                    "Last_Name" => "Panwar",
                    "First_Name" => "Manoj",
                    "Email" => "manoj.panwar@asterhealthacademy.com",
                    "State" => "Uttarakhand",
                    "City" => 'Dehradun',
                    'Country' => 'India',
                    'Phone' => '9084066678',
                    'Zip_Code' => '248002',
                    'Street' => 'Haridwar Bypass Rd, Clement Town',
                    'Street_2' => 'Dehradun, Uttarakhand 248002'
                ] 
            ],
            "apply_feature_execution" => [
                [
                    "name" => "layout_rules"
                ]
            ],
            "trigger" => [
                "approval",
                "workflow",
                "blueprint"
            ]
        ];

        $data = json_encode($data, true);
        $curl = curl_init($apiUrl);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Zoho-oauthtoken " . $accessToken,
            "Content-Type: application/json",
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode === 200) {
            echo "Request was successful. Response:\n" . $response;
        } else {
            echo "Request failed with HTTP status code " . $httpCode;
        }

        curl_close($curl);
    }

    public function deleteLead(){
        $apiUrl = "https://www.zohoapis.in/crm/v2/Leads?ids=444231000005244538&wf_trigger=true";
        // get contents of a file into a string
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $token =  json_decode($contents, true);
        $accessToken = $token['access_token'];

        $curl = curl_init($apiUrl);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Zoho-oauthtoken " . $accessToken
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode === 204) {
            echo "Request was successful. The records have been deleted.";
        } else {
            echo "Request failed with HTTP status code " . $httpCode;
        }

        curl_close($curl);
        
    
    }

    public function createCustomer(Request $request){

        $customerExist = Customer::where('email', $request->email)->orWhere('phone', $request->phone)->first();
        $currency = Currency::where('code', $request->currency)->first();
        if(isset($customerExist)){
            $customerExist->name = $request->name;
            $customerExist->email = $request->email;
            $customerExist->phone = $request->phone;
            $customerExist->company_id = 1;
            if(isset($currency)){
                $customerExist->currency_id = $currency->id;
            }
            $customerExist->update();
            return json_encode(['customer_id' => $customerExist->id, 'currency_id' => $currency->id, 'message' => 'Customer updated.'], 200);

        }else{
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->company_id = 1;
    
            $currency = Currency::where('code', $request->currency)->first();
            if(isset($currency)){
                $customer->currency_id = $currency->id;
            }

            $customer->save();
            return json_encode(['customer_id' => $customer->id, 'currency_id' => $currency->id, 'message' => 'Customer Added'], 200);
        }
        
    }

    public function createInvoice(Request $request){
        $invoice = new Invoice();
       
        $invoice->reference_number = NULL;

        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->invoice_due_date;
        
        
        $invoice->status = 'DRAFT';
        $invoice->paid_status = 'UNPAID';

        $invoice->tax_per_item = 'YES';
        $invoice->discount_per_item = 'YES';
       
        $invoice->discount = $request->invoice_discount;
        $invoice->discount_type = 'fixed';
        $invoice->discount_val = $request->invoice_discount;
        $invoice->sub_total = $request->invoice_sub_total;
        $invoice->total = $request->invoice_total;
        $invoice->tax = $request->invoice_tax;
        $invoice->due_amount = $request->invoice_total;

        $invoice->company_id = 1;
        $invoice->creator_id = 1;
        $invoice->customer_id = $request->customer_id;
        $invoice->currency_id = $request->currency_id;

        $invoice->exchange_rate = NULL;
        $invoice->base_discount_val = $request->invoice_discount;
        $invoice->base_sub_total = $request->invoice_sub_total;
        $invoice->base_total = $request->invoice_total;
        $invoice->base_tax = $request->invoice_tax;
        $invoice->base_due_amount = $request->invoice_total;
        
        $invoice->sales_tax_type = NULL;
        $invoice->sales_tax_address_type = NULL;
        $invoice->overdue = 0;

        $invoice->save();

        $invoiceLast = Invoice::where('id', $invoice->id)->first();

        $invoiceNumber = 000000 + $invoice->id;
        $invoiceLast->sequence_number = $invoice->id;
        $invoiceLast->customer_sequence_number = $invoice->id;
        $invoiceLast->invoice_number = $invoiceNumber;
        $invoiceLast->update();

    }

    public function syncZohoUsers(){
      $zohoUsers = $this->getZohoUsers();
      if(isset($zohoUsers)){
        if(count($zohoUsers) > 0){
            $updateUsers = User::where('id', '>', 0)->where('role', '!=', 'super admin')->update(['zoho_sync' => 0,
            'is_deleted' => 1, 'zoho_status_active' => 0]);
            foreach($zohoUsers as $zohoUser){
                $zohoUserExist = User::where('zoho_users_id', $zohoUser['id'])->where('role', '!=', 'super admin')->first();
                if(!isset($zohoUserExist)){

                    $userArray = [
                        'name' => $zohoUser['full_name'],
                        'email' => $zohoUser['email'],
                        'phone' => $zohoUser['phone'],
                        'zoho_users_id' => $zohoUser['id'],
                        'is_deleted' => 0,
                        'zoho_sync' => 1
                    ];
                    
                    if(isset($zohoUser['role']['id'])){
                        $userArray['zoho_role_id'] = $zohoUser['role']['id'];
                    }

                    if(isset($zohoUser['profile']['id'])){
                        $userArray['zoho_profile_id'] = $zohoUser['profile']['id'];
                        $userArray['zoho_profile_name'] = $zohoUser['profile']['name']; 
                    }

                    if(isset($zohoUser['status'])){
                        if($zohoUser['status'] == 'active'){
                            $userArray['zoho_status_active'] = 1;
                        }
                    }

                    if(isset($zohoUser['role']['name'])){
                        $zohoUserRoleName = $zohoUser['role']['name'];
                        if (preg_match('/sales/i', $zohoUserRoleName)) {
                            $userArray['role'] = 'sales executive';
                        }else{
                            $userArray['role'] = 'standard';
                        } 
                    }else{
                        $userArray['role'] = 'standard';
                    }

                    $zohoNewUser = User::create($userArray);
                    
                    BouncerFacade::scope()->to(1);
                    BouncerFacade::sync($zohoNewUser)->roles([$userArray['role']]);

                    $zohoNewUser->companies()->attach(1);
                    
                }else{
                    $zohoUserExist->name = $zohoUser['full_name'];
                    $zohoUserExist->email = $zohoUser['email'];
                    $zohoUserExist->phone = $zohoUser['phone'];
                    $zohoUserExist->zoho_users_id = $zohoUser['id'];
    
                    if(isset($zohoUser['role']['id'])){
                        $zohoUserExist->zoho_role_id = $zohoUser['role']['id'];
                    }
    
                    if(isset($zohoUser['profile']['id'])){
                        $zohoUserExist->zoho_profile_id = $zohoUser['profile']['id'];
                        $zohoUserExist->zoho_profile_name = $zohoUser['profile']['name']; 
                    }
    
                    if(isset($zohoUser['status'])){
                        if($zohoUser['status'] == 'active'){
                            $zohoUserExist->zoho_status_active = 1;
                        }
                    }
                    
                    $zohoUserExist->is_deleted = 0;
                    $zohoUserExist->zoho_sync = 1;

                    $zohoUserExist->update();

                }
            }
        }
        
      }
    }

    public function getZohoUsers(){
        $zohoUsers = [];
        $url = "https://www.zohoapis.in/crm/v2/users?";
       
        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

        $counter=1;
        $per_page = 1;
        for($i=0;$i<$counter;$i++){
            $parameters = array();
            $parameters["type"]="AllUsers";
            $parameters["page"]=$per_page;
            $parameters["per_page"]=10;
    
            $getZohoUsers = $this->zohoUsers($url, $parameters, $access_token);
            if(isset($getZohoUsers['info']['more_records'])){
                foreach($getZohoUsers['users'] as $users){
                    $zohoUsers[] = $users;
                }
                if($getZohoUsers['info']['more_records'] == true){
                    $counter++;
                    $per_page++;
                }
            }
        }
        return $zohoUsers;
    }

    public function zohoUsers($url, $parameters, $access_token){
        $curl_pointer = curl_init();
        $curl_options = array();

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

    public function syncZohoRoles(){
        $syncZohoRoles = $this->getZohoRoles();
        if(isset($syncZohoRoles['roles'])){
            $updateZohoRoles = ZohoRole::where('id', '>', 0)->update(['is_deleted' => 1,'is_active_zoho' => 0, 'zoho_sync' => 0]);
            foreach($syncZohoRoles['roles'] as $zohoRole){
                $zohoRoleId = $zohoRole['id'];
                $zohoRoleExist = ZohoRole::where('role_id', $zohoRoleId)->first();
                if(!isset($zohoRoleExist)){
                    $addNewZohoRole = new ZohoRole();
                    $addNewZohoRole->role_id = $zohoRole['id'];
                    $addNewZohoRole->role_name = $zohoRole['name'];
                    if(isset($zohoRole['reporting_to']['id'])){
                        $addNewZohoRole->reporting_manager_zoho = $zohoRole['reporting_to']['id'];
                    }
                    $addNewZohoRole->max_discount_allowed = NULL;
                    $addNewZohoRole->is_deleted = 0;
                    $addNewZohoRole->is_active_zoho = 1;
                    $addNewZohoRole->zoho_sync = 1;
                    $addNewZohoRole->created_by = 2;
                    $addNewZohoRole->updated_by = 2;
                    $addNewZohoRole->save();

                    $addNewZohoRole->reporting_manager = $addNewZohoRole->id;
                    $addNewZohoRole->update();
                }else{
                    $zohoRoleExist->role_id = $zohoRole['id'];
                    $zohoRoleExist->role_name = $zohoRole['name'];
                    if(isset($zohoRole['reporting_to']['id'])){
                        $zohoRoleExist->reporting_manager_zoho = $zohoRole['reporting_to']['id'];
                    }
                    $zohoRoleExist->max_discount_allowed = NULL;
                    $zohoRoleExist->is_deleted = 0;
                    $zohoRoleExist->is_active_zoho = 1;
                    $zohoRoleExist->zoho_sync = 1;
                    $zohoRoleExist->created_by = 2;
                    $zohoRoleExist->updated_by = 2;
                    $zohoRoleExist->reporting_manager = $zohoRoleExist->id;
                    $zohoRoleExist->update();
                }
            }
        }
        return true;
    }

    public function getZohoRoles(){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        $url = "https://www.zohoapis.in/crm/v2/settings/roles";

        $filename = base_path()."/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

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

    public function zohoRolesErp(){
        $zohoRoles = [];
        $getZohoRoles = ZohoRole::where('id', '>', 0)->get();
        foreach($getZohoRoles as $eachZohoRole){
            $zohoRole = [];
            $zohoRole['zoho_roles_erp_id'] = $eachZohoRole->id;
            $zohoRole['role_id'] = $eachZohoRole->role_id;
            $zohoRole['role_name'] = $eachZohoRole->role_name;
            $zohoRole['reporting_manager_zoho'] = $eachZohoRole->reporting_manager_zoho;
            $zohoRole['reporting_manager'] = $eachZohoRole->reporting_manager;
            $zohoRole['max_discount_allowed'] = $eachZohoRole->max_discount_allowed;
            $zohoRole['is_deleted'] = $eachZohoRole->is_deleted;
            $zohoRole['is_active_zoho'] = $eachZohoRole->is_active_zoho;
            $zohoRole['zoho_sync'] = $eachZohoRole->zoho_sync;
            $zohoRole['created_by'] = $eachZohoRole->created_by;
            $zohoRole['updated_by'] = $eachZohoRole->updated_by;
            $zohoRoles[] = $zohoRole;
        }

        return json_encode(['zoho_roles' => $zohoRoles]);
    }

    public function erpUsers(){
        $erpUsers = [];
        $users = User::whereIn('role', ['standard', 'sales executive', 'management'])->get();
        if(count($users) > 0){
            foreach($users as $eachUser){
                $user = [];
                $user['erp_users_id'] = $eachUser->id;
                $user['name'] = $eachUser->name;
                $user['email'] = $eachUser->email;
                $user['phone'] = $eachUser->phone;
                $user['role'] = $eachUser->role;
                $user['contact_name'] = $eachUser->contact_name;
                $user['company_name'] = $eachUser->company_name;
                $user['zoho_role_id_xcelerate'] = $eachUser->zoho_role_id_xcelerate;
                $user['zoho_role_id'] = $eachUser->zoho_role_id;
                $user['zoho_users_id'] = $eachUser->zoho_users_id;
                $user['zoho_profile_id'] = $eachUser->zoho_profile_id;
                $user['zoho_profile_name'] = $eachUser->zoho_profile_name;
                $user['zoho_status_active'] = $eachUser->zoho_status_active;
                $user['is_deleted'] = $eachUser->is_deleted;
                $user['zoho_sync'] = $eachUser->zoho_sync;
                $erpUsers[] = $user;
            }
        }

        return response()->json($erpUsers, 200);
    }

    public function erpProducts(){
        $erpProducts = [];
        $products = Item::where('is_deleted', '0')->get();
        foreach($products as $eachProduct){
            $product = [];
            $product['id'] = $eachProduct->id;
            $product['name'] = $eachProduct->name;
            $product['description'] = $eachProduct->description;
            $product['price'] = $eachProduct->price;
            $product['company_id'] = $eachProduct->company_id;
            $product['unit_id'] = $eachProduct->unit_id;
            $product['currency_id'] = $eachProduct->currency_id;
            $product['currency_symbol'] = $eachProduct->currency_symbol;
            $product['tax_per_item'] = $eachProduct->tax_per_item;
            $product['price_aed'] = $eachProduct->price_aed;
            $product['price_saarc'] = $eachProduct->price_saarc;
            $product['price_us'] = $eachProduct->price_us;
            $product['price_row'] = $eachProduct->price_row;
            $product['is_sync'] = $eachProduct->is_sync;
            $product['zoho_crm_id'] = $eachProduct->zoho_crm_id;
            $product['sync_date_time'] = $eachProduct->sync_date_time;
            $product['item_code'] = $eachProduct->item_code;
            $erpProducts[] = $product;
        }

        return response()->json($erpProducts, 200);

    }

    public function erpTaxTypes(){
        $erpTaxTypes = [];
        $taxTypes = TaxType::get();
        foreach($taxTypes as $eachTaxType){
            $taxType = [];
            $taxType['id'] = $eachTaxType->id;
            $taxType['name'] = $eachTaxType->name;
            $taxType['percent'] = $eachTaxType->percent;
            $taxType['compound_tax'] = $eachTaxType->compound_tax;
            $taxType['collective_tax'] = $eachTaxType->collective_tax;
            $taxType['description'] = $eachTaxType->description;
            $taxType['company_id'] = $eachTaxType->company_id;
            $taxType['type'] = $eachTaxType->type;
            $erpTaxTypes[] = $taxType;
        }

        return response()->json($erpTaxTypes, 200);
    }
}
