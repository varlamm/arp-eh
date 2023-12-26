<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\CrmConnector;

class ZohoSyncRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-sync-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch roles from zoho crm and then update all roles in zoho_roles table.';

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
        $message = 'Roles upload failed.';
        $companies = CompanySetting::where('option', 'company_crm')
                        ->where('value', '<>','none')
                        ->get()
                        ->toArray();

        if(count($companies) > 0){
            foreach($companies as $company){
                if(isset($company['company_id'])){
                    $crmConnectorObj = $this->initiate();
                    $roleSync = $crmConnectorObj->syncRoles($company['company_id']);
                    if(isset($roleSync['response'])){
                        if($roleSync['response'] == true){
                            $return = true;
                        }
                        
                        $message = $roleSync['message'];
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
