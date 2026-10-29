<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesColumnToFileExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_extends', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('delete_by')->nullable();
            $table->foreign('delete_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_extends', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['delete_by']);
            $table->dropColumn(['delete_by']);
        });
    }
}
