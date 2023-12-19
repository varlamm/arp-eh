<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyFieldsAndCrmStandardMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->string('column_name');
            $table->string('label')->nullable();
            $table->string('table_name');
            $table->string('column_type');
            $table->string('options');
            $table->tinyInteger('boolean');
            $table->date('date');
            $table->time('time');
            $table->string('text');
            $table->bigInteger('number');
            $table->dateTime('date_time');
            $table->tinyInteger('is_required');
            $table->string('crm_mapped_field');
            $table->enum('field_type', ['standard', 'custom'])->default('standard');
            $table->enum('is_unique', ['yes', 'no'])->default('no');
            $table->enum('visiblity', ['visible', 'hidden', 'locked'])->default('visible');
            $table->integer('order_listing_page')->nullable();
            $table->integer('order_form_page')->nullable();
            $table->enum('listing_page', ['yes', 'no'])->default('no');
            $table->timestamps();
        });

        Schema::create('crm_standard_mappings', function(Blueprint $table) {
            $table->id();
            $table->char('crm_name', 100);
            $table->char('field_name', 100);
            $table->char('crm_column_name', 100);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('company_fields_and_crm_standard_mapping');
    }
}
