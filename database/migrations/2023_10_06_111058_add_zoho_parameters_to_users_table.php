<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZohoParametersToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('zoho_role_id_crater')->nullable();
            $table->bigInteger('zoho_role_id')->nullable();
            $table->bigInteger('zoho_users_id')->nullable();
            $table->bigInteger('zoho_profile_id')->nullable();
            $table->char('zoho_profile_name', 255)->nullable();
            $table->integer('zoho_status_active')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->integer('zoho_sync')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
