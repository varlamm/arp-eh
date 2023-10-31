<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;
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
    public function __construct()
    {
        parent::__construct();
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

    }
}
