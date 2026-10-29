<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorColumnToConventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convections', function (Blueprint $table) {
            $table->string('color')->default('#000000')->after('slug')->nullable();
            $table->dropColumn('avatar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convections', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->string('avatar')->after('slug')->nullable();
        });
    }
}
