<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;
use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\CrmConnector;

class ZohoAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate zoho refresh token using command.';

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
        if(!isset(self::$instance)){
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
        $companies = CompanySetting::where('option', 'company_crm')
                        ->where('value', 'zoho')
                        ->get()
                        ->toArray();

        if(count($companies) > 0){
            foreach($companies as $company){
                if(isset($company['company_id'])){
                    $crmConnectorObj = $this->initiate();
                    $generateRefereshToken = $crmConnectorObj->generateRefreshToken($company['company_id']);
                    if($generateRefereshToken){
                       $return = true;
                    }
                }
            }
        }

        if($return){
            $this->info('Refresh Token generated successfully.');
        }
        else {
            $this->info('Refresh Token generation failed.');
        }
        exit;
    }
}
