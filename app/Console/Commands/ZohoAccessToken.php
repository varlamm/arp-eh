<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\ZohoToken;

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
    protected $description = 'Regenerate Access Token from command line.';

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
        $generateAccessToken = $this->zohoController->generateRefreshToken($this->zohoToken);
        return true;
    }
}
