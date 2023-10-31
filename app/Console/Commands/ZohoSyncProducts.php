<?php

namespace Xcelerate\Console\Commands;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;
use Illuminate\Console\Command;

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
    public function __construct(ZohoController $zohoController, ZohoToken $zohoToken)
    {
        parent::__construct();
        $this->zohoController = $zohoController;
        $this->zohoToken = $zohoToken;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $syncZohoProducts = $this->zohoController->syncProducts();
        if($syncZohoProducts){
            $this->info('Products synced successfully.');
        }
    }
}
