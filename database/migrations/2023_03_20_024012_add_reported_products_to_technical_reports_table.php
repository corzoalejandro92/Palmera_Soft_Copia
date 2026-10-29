<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportedProductsToTechnicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_reports', function (Blueprint $table) {
            $table->json('reported_products')->nullable();
            $table->bigInteger('crm_external_note_id')->nullable()->unsigned();
            $table->foreign('crm_external_note_id')->references('id')->on('crm_external_notes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_reports', function (Blueprint $table) {
            $table->dropColumn('reported_products');
            $table->dropForeign(['crm_external_note_id']);
            $table->dropColumn('crm_external_note_id');
        });
    }
}
