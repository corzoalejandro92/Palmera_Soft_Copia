<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeObjetiveColumnToObjetivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->string('code',40)->nullable();
            $table->bigInteger('type_objetive_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->dropColumn(['type_objetive_id','code']);
        });
    }
}
