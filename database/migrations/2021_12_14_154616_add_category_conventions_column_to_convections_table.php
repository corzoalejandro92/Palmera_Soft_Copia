<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryConventionsColumnToConvectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convections', function (Blueprint $table) {
            $table->unsignedBigInteger('category_conventions_id')->nullable();
            $table->foreign('category_conventions_id')->references('id')->on('category_conventions');
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
            $table->dropForeign(['category_conventions_id']);
            $table->dropColumn(['category_conventions_id']);
        });
    }
}
