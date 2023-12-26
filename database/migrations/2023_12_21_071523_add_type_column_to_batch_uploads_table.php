<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToBatchUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_uploads', function (Blueprint $table) {
            $table->char('type', 50)->after('file_name')->default('CSV');
        });

        Schema::table('batch_upload_records', function (Blueprint $table) {
            $table->integer('request_log_id')->after('message')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_uploads', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('batch_upload_records', function (Blueprint $table) {
            $table->dropColumn('request_log_id');
        });
    }
}
