<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewedColumnToSchedulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedulings', function (Blueprint $table) {
            $table->boolean('reviewed')->default(0);
            $table->timestamp('reviewed_at')->nullable();
            $table->bigInteger('reviewed_by')->nullable()->unsigned();
            $table->foreign('reviewed_by')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedulings', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn('reviewed_by');
            $table->dropColumn('reviewed_at');
            $table->dropColumn('reviewed');
        });
    }
}
