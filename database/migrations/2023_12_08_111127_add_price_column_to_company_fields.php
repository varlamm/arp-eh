<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnToCompanyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_fields', function (Blueprint $table) {
            $table->float('price', 10, 2)->after('text')->nullable();
            $table->tinyInteger('creator_id')->after('listing_page')->default(1);
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
            $table->dropColumn('price');
            $table->dropColumn('creator_id');
        });
    }
}
