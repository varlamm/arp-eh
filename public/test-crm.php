<?php
class GetRecords{
    
    public function execute( $url, $parameters ){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        // $url = "https://www.zohoapis.in/crm/v2/Leads?";
        $parameters = array();
        $parameters["page"]="1";
        $parameters["per_page"]="20";
        $parameters["sort_by"]="Email";
        $parameters["sort_order"]="desc";
        $parameters["include_child"]="true";

        // get contents of a file into a string
        $filename = "C:/xampp/htdocs/aha-erp/storage/oauth_access_token.txt";
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $data =  json_decode($contents, true);
        $access_token = $data['access_token'];

        foreach ($parameters as $key=>$value){
            $url =$url.$key."=".$value."&";
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
    
}

    $url = "https://www.zohoapis.in/crm/v2/Products?";
    $parameters = array();
    $parameters["page"]="1";
    $parameters["per_page"]="20";
    $parameters["sort_by"]="Modified_Time";
    $parameters["sort_order"]="desc";
    $parameters["include_child"]="true";


	$zohoRecords  = new GetRecords();
	$data =  $zohoRecords->execute($url, $parameters);
	echo "<pre>";
	print_r($data);

