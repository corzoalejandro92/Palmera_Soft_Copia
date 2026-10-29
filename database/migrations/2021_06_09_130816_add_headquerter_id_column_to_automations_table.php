<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeadquerterIdColumnToAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automations', function (Blueprint $table) {
            $table->integer('headquarter_id')->after('user_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automations', function (Blueprint $table) {
            $table->dropForeign(['headquarter_id']);
            $table->dropColumn('headquarter_id');
        });
    }
}
