<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnStatusType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'crm_status_active')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('crm_status_active');
                });
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->char('crm_status', 50)->after('crm_profile_name')->default('inactive');
            $table->integer('company_id')->after('role')->nullable();
            $table->dateTime('sync_date_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('=users', function (Blueprint $table) {
            //
        });
    }
}
