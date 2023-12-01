<?php

use Xcelerate\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class UpdateXcelerateVersion501 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::setSetting('version', '5.0.1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
