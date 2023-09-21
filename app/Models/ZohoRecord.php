<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use com\zoho\crm\api\util\Constants;
use com\zoho\api\authenticator\OAuthToken;
use com\zoho\api\authenticator\store\DBStore;
use com\zoho\api\authenticator\store\FileStore;
use com\zoho\crm\api\Initializer;

use com\zoho\crm\api\UserSignature;

use com\zoho\crm\api\SDKConfigBuilder;

use com\zoho\crm\api\dc\USDataCenter;

use com\zoho\api\logger\Logger;
use com\zoho\api\logger\Levels;
use com\zoho\crm\api\record\RecordOperations;

use com\zoho\crm\api\HeaderMap;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\record\GetRecordsHeader;

use com\zoho\crm\api\record\GetRecordsParam;

use com\zoho\crm\api\record\ResponseWrapper;

class ZohoRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const REFRESH = Constants::REFRESH;

    const GRANT = Constants::GRANT;

    public function initialize()
    {
        $client_id = env('ZOHO_CLIENT_ID');
        $client_secret = env('ZOHO_CLIENT_SECRET');
        $call_back_uri = env('APP_URL')."/".env('ZOHO_CALLBACK_URI');
        //Create a Token instance
        
        $token = new OAuthToken($client_id, $client_secret, "GRANT", self::GRANT, $call_back_uri);

        //Create an instance of TokenStore
        // $tokenstore = new DBStore();
        $storage_path = storage_path('php_sdk_token.txt');

        $tokenstore = new FileStore($storage_path);

        $autoRefreshFields = false;

        $pickListValidation = false;

        $enableSSLVerification = false;

        $connectionTimeout = 2;

        $timeout = 2;

        $sdkConfig = (new SDKConfigBuilder())->setAutoRefreshFields($autoRefreshFields)->setPickListValidation($pickListValidation)->setSSLVerification($enableSSLVerification)->connectionTimeout($connectionTimeout)->timeout($timeout)->build();

        $resourcePath = base_path().'/vendor/zohocrm/php-sdk';

       /*
        * Call static initialize method of Initializer class that takes the following arguments
        * 1 -> UserSignature instance
        * 2 -> Environment instance
        * 3 -> Token instance
        * 4 -> TokenStore instance
        * 5 -> SDKConfig instance
        * 6 -> resourcePath -A String
        * 7 -> Log instance (optional)
        * 8 -> RequestProxy instance (optional)
        */

        $user = new UserSignature("manoj.panwar@asterhealthacademy.com");
        $environment = USDataCenter::PRODUCTION();
        $logger_path = storage_path('php_sdk_log.log');
        $logger = Logger::getInstance(Levels::INFO, $logger_path);

        Initializer::initialize($user, $environment, $token, $tokenstore, $sdkConfig, $resourcePath, $logger);

        try
        {
            $recordOperations = new RecordOperations();

            $paramInstance = new ParameterMap();

            $paramInstance->add(GetRecordsParam::approved(), "both");

            $headerInstance = new HeaderMap();

            $ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $headerInstance->add(GetRecordsHeader::IfModifiedSince(), $ifmodifiedsince);

            $moduleAPIName = "Leads";

            //Call getRecord method that takes paramInstance, moduleAPIName as parameter
            $response = $recordOperations->getRecords($moduleAPIName,$paramInstance, $headerInstance);

            if($response != null)
            {
                //Get the status code from response
                echo("Status Code: " . $response->getStatusCode() . "\n");

                //Get object from response
                $responseHandler = $response->getObject();

                if($responseHandler instanceof ResponseWrapper)
                {
                    //Get the received ResponseWrapper instance
                    $responseWrapper = $responseHandler;

                    //Get the list of obtained Record instances
                    $records = $responseWrapper->getData();

                    if($records != null)
                    {
                        $recordClass = 'com\zoho\crm\api\record\Record';

                        foreach($records as $record)
                        {
                            //Get the ID of each Record
                            echo("Record ID: " . $record->getId() . "\n");

                            //Get the createdBy User instance of each Record
                            $createdBy = $record->getCreatedBy();

                            //Check if createdBy is not null
                            if($createdBy != null)
                            {
                                //Get the ID of the createdBy User
                                echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

                                //Get the name of the createdBy User
                                echo("Record Created By User-Name: " . $createdBy->getName() . "\n");

                                //Get the Email of the createdBy User
                                echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
                            }

                            //Get the CreatedTime of each Record
                            echo("Record CreatedTime: ");

                            print_r($record->getCreatedTime());

                            echo("\n");

                            //Get the modifiedBy User instance of each Record
                            $modifiedBy = $record->getModifiedBy();

                            //Check if modifiedBy is not null
                            if($modifiedBy != null)
                            {
                                //Get the ID of the modifiedBy User
                                echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

                                //Get the name of the modifiedBy User
                                echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");

                                //Get the Email of the modifiedBy User
                                echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                            }

                            //Get the ModifiedTime of each Record
                            echo("Record ModifiedTime: ");

                            print_r($record->getModifiedTime());

                            print_r("\n");

                            //Get the list of Tag instance each Record
                            $tags = $record->getTag();

                            //Check if tags is not null
                            if($tags != null)
                            {
                                foreach($tags as $tag)
                                {
                                    //Get the Name of each Tag
                                    echo("Record Tag Name: " . $tag->getName() . "\n");

                                    //Get the Id of each Tag
                                    echo("Record Tag ID: " . $tag->getId() . "\n");
                                }
                            }

                            //To get particular field value
                            echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName

                            echo("Record KeyValues : \n" );
                            //Get the KeyValue map
                            foreach($record->getKeyValues() as $keyName => $value)
                            {
                                echo("Field APIName" . $keyName . " \tValue : ");

                                print_r($value);

                                echo("\n");
                            }
                        }
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            print_r($e);
        }
    }
}

