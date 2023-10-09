<?php

namespace Crater\Console\Commands;

use Illuminate\Console\Command;
use Crater\Http\Controllers\ZohoController;

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
        $syncZohoRoles = new ZohoController();
        $syncZohoRoles->syncZohoRoles();
    }
}
