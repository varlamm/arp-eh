<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->enum('status', ['processed', 'processing', 'uploaded'])->default('uploaded');
            $table->string('model')->nullable();
            $table->string('mapped_fields')->nullable();
            $table->dateTime('notify_time')->nullable();
            $table->integer('created_by')->default(1);
            $table->dateTime('completed_time')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_upload_records', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->nullable();
            $table->string('row_data')->nullable();
            $table->enum('status', ['created', 'updated', 'failed'])->nullable();
            $table->char('message', 100)->nullable();
            $table->integer('process_counter')->default(1);
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
        Schema::dropIfExists('batch_upload');
    }
}
