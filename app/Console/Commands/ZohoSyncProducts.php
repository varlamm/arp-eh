<?php

namespace Xcelerate\Console\Commands;
use Illuminate\Console\Command;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;
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
    protected $description = 'Fetch all products from Zoho CRM and sync them with the items table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $zohoController;
    protected $zohoToken;
    private static $instance;

    public function __construct(ZohoController $zohoController, ZohoToken $zohoToken)
    {
        parent::__construct();
        $this->zohoController = $zohoController;
        $this->zohoToken = $zohoToken;
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
        $message = 'Items sync failed.';
        $companies = CompanySetting::where('option', 'company_crm')
                        ->where('value', 'zoho')
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
