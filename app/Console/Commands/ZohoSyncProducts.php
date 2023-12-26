<?php

namespace Xcelerate\Console\Commands;
use Illuminate\Console\Command;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\CrmConnector;

class ZohoSyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-sync-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all products from Zoho CRM and upload them into batch_upload and batch_upload_records table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public function initiate(){
        if(!self::$instance){
            self::$instance = new CrmConnector();
        }

        return self::$instance;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $return = false;
        $message = 'Item upload failed.';
        $companies = CompanySetting::where('option', 'company_crm')
                        ->where('value', '<>','none')
                        ->get()
                        ->toArray();

        if(count($companies) > 0){
            foreach($companies as $company){
                if(isset($company['company_id'])){
                    $crmConnectorObj = $this->initiate();
                    $itemSync = $crmConnectorObj->syncProducts($company['company_id']);
                    if(isset($itemSync['response'])){
                        if($itemSync['response'] == true){
                            $return = true;
                        }
                        $message = $itemSync['message'];
                    }
                }
            }
        }

        if($return){
            $this->info($message);
        }
        else{
            $this->info($message);
        }
        exit;
    }
}
