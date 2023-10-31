<?php

use Xcelerate\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class UpdateXcelerateVersion606 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::setSetting('version', '6.0.6');
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
