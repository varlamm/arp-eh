<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\CrmConnector;
class ZohoSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $zohoController = new ZohoController();
        $syncZohoUsers = $zohoController->syncZohoUsers();
        $return = false;
        $message = 'Users sync failed.';
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
