<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdColumnToCrmRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_roles', function (Blueprint $table) {
            $table->dropColumn('is_active_crm');
            $table->dropColumn('crm_sync');
            $table->char('company_id', 50)->after('role_name')->nullable();
            $table->dateTime('sync_date_time')->after('updated_by')->nullable();
            $table->integer('is_sync')->after('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_roles', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('sync_date_time');
            $table->dropColumn('is_sync');
        });
    }
}
