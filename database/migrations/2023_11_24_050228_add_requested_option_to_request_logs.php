<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRequestedOptionToRequestLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('request_logs', 'request_time'))
        {
            Schema::table('request_logs', function (Blueprint $table)
            {
                $table->dropColumn('request_time');
            });
        }

        if (Schema::hasColumn('request_logs', 'response_time'))
        {
            Schema::table('request_logs', function (Blueprint $table)
            {
                $table->dropColumn('response_time');
            });
        }

        Schema::table('request_logs', function (Blueprint $table) {
            DB::statement("ALTER TABLE request_logs MODIFY COLUMN status ENUM('REQUESTED', 'SUCCESS', 'FAILED') DEFAULT 'REQUESTED'");
            
            $table->dateTime('request_time')->after('response_message');
            $table->dateTime('response_time')->after('request_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_logs', function (Blueprint $table) {
            //
        });
    }
}
