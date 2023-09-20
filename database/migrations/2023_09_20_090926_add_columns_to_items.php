<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {

            if (!Schema::hasColumn('items', 'price_aed')) {
                $table->float('price_aed', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('items', 'price_saarc')) {
                $table->float('price_saarc', 10, 2)->nullable();      
            }

            if (!Schema::hasColumn('items', 'price_us')) {
                $table->float('price_us', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('items', 'price_row')) {
                $table->float('price_row', 10, 2)->nullable(); 
            }

            if (!Schema::hasColumn('items', 'is_sync')) {
                $table->tinyInteger('is_sync')->default(0); 
            }

            if (!Schema::hasColumn('items', 'zoho_crm_id')) {
                $table->bigInteger('zoho_crm_id')->nullable();       
            }
            
            if (!Schema::hasColumn('items', 'sync_date_time')) {
                $table->dateTime('sync_date_time')->nullable();       
            }
            
            $table->string('item_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
