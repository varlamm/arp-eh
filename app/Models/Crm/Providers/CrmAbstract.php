<?php
namespace Xcelerate\Models\Crm\Providers;

use Xcelerate\Models\RequestLog;

abstract class CrmAbstract
{
    abstract function initialize();

    private static $logInstance;
    
    public static $logId;

    public function initiateLogInstance(){
        if(!isset($logInstance)){
            self::$logInstance = new RequestLog();
        }

        return self::$logInstance;
    }

    public function curlRequest($url, $type, $companyId, $parameters, $method="GET", $headersArray=[], $skip_response_log=false){
        $curl_pointer = curl_init();
        $curl_options = array();
        
        foreach ($parameters as $key=>$value){
            $url = $url.$key."=".$value."&";
        }

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = $method;
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
        curl_setopt_array($curl_pointer, $curl_options);
        
        self::saveLog($url, $type, $companyId, $parameters, $method, $headersArray, 'request');

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);

        if($skip_response_log){
            return $result;
        }else{
            self::saveLog($url, $type, $companyId, $parameters, $method, $headersArray, 'response');
            return $result;
        }
    }

    public static function saveLog($url, $type, $companyId, $parameters, $method="GET", $headersArray=[], $requestType, $requestBody=NULL, $responseCode=NULL, $responseMessage=NULL, $responseBody=NULL){
        if($requestType === 'request'){
            $requestLog = new RequestLog();
            $requestLog->company_id = $companyId;
            $requestLog->type = $type;
            $requestLog->request_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname(gethostname());
    
            $requestLog->status = 'REQUESTED';
            $requestLog->request_url = $url;
            $requestLog->request_method = $method;
            $requestLog->request_params = json_encode($parameters, true);
            $requestLog->request_headers = json_encode($headersArray, true);
            $requestLog->request_time = date("Y-m-d H:i:s");
            $requestLog->save();

            self::$logId = $requestLog->id;
        }
        else if($requestType == 'response'){
            $requestLog = RequestLog::where('id', self::$logId)->first();
            $requestLog->response_body = $responseBody;
            $requestLog->response_code = $responseCode;
            $requestLog->response_message = $responseMessage;

            $requestLog->status = 'FAILED';
            if($responseCode == 200){
                $requestLog->status = 'SUCCESS';
            }
            $requestLog->response_time = date("Y-m-d H:i:s");
            $requestLog->update();
        }
        
    }
}