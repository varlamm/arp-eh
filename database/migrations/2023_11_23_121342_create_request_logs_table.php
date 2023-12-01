<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->char('company_id', 8);
            $table->string('type');
            $table->enum('status', ['SUCCESS', 'FAILED']);
            $table->char('response_code', 10)->nullable();
            $table->text('response_message')->nullable();
            $table->timestamp('request_time');
            $table->timestamp('response_time');
            $table->text('request_url');
            $table->string('request_method');
            $table->text('request_params');
            $table->text('request_headers');
            $table->longText('request_body')->nullable();
            $table->longText('response_body')->nullable();
            $table->string('request_ip');
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
        Schema::dropIfExists('request_logs');
    }
}
