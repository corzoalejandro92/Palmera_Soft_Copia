<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeToFileExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_extends', function (Blueprint $table) {
            $table->integer('size')->after('y')->default(30);
            $table->integer('num')->after('size')->nullable();
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
            $table->dropColumn('size');
            $table->dropColumn('num');
        });
    }
}
