<?php

use Xcelerate\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class UpdateXcelerateVersion602 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::setSetting('version', '6.0.2');
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
