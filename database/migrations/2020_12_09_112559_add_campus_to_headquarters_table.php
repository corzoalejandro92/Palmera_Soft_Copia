<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampusToHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->string('cell_phone',50)->nullable()->after('phone');
            $table->string('email',50)->nullable()->after('cell_phone',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->dropColumn('cell_phone',50);
            $table->dropColumn('email',50);
        });
    }
}
