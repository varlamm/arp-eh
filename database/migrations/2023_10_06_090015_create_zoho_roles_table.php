<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZohoRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id');
            $table->char('role_name', 255)->nullable();
            $table->bigInteger('reporting_manager_crm')->nullable();
            $table->bigInteger('reporting_manager')->nullable();
            $table->integer('max_discount_allowed')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->integer('is_active_crm')->default(0);
            $table->integer('crm_sync')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_roles');
    }
}
