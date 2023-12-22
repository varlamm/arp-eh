<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemDefinedColumnToCompanyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_fields', function (Blueprint $table) {
            $table->enum('is_system', ['yes', 'no'])->default('no')->after('is_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_fields', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
}
