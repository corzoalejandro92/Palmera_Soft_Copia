<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceNewColumnToCrmExternalNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_external_notes', function (Blueprint $table) {
            $table->bigInteger('value')->nullable();
            $table->string('number',20)->nullable();
            $table->date('invoice_date')->nullable();
            $table->boolean('new_service')->default(false)->nullable();
            $table->unsignedBigInteger('commercial_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_external_notes', function (Blueprint $table) {
            $table->dropColumn(['value','number','commercial_id','invoice_date','new_service']);
        });
    }
}
