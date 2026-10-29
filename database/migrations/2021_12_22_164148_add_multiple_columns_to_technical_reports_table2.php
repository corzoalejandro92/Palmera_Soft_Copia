<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToTechnicalReportsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('technical_reports', function (Blueprint $table) {
            $table->bigInteger('area_office_id')->nullable()->unsigned()->after('file_extend_id');
            $table->foreign('area_office_id')->references('id')->on('area_offices')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('file_id')->nullable()->unsigned()->after('file_extend_id');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('x',20)->nullable()->after('area_office_id');
            $table->string('y',20)->nullable()->after('x');
            $table->string('qr_token',100)->nullable()->after('y');
        });

        //rellenar estos campos con los datos ya existentes
        $technicalReports = \App\TechnicalReport::with('extend.file')->get();
        if(!is_null($technicalReports)){
            foreach($technicalReports as $tr){
                $tr->x = $tr->extend ? $tr->extend->x : null;
                $tr->y = $tr->extend ? $tr->extend->y : null;
                $tr->file_id = $tr->extend && $tr->extend->file ? $tr->extend->file->id : null;
                $tr->area_office_id = $tr->extend  && $tr->extend->file ? $tr->extend->file->filable_id: null;
                $tr->qr_token=  $tr->extend && $tr->extend->qr ? $tr->extend->qr->token: null;
                $tr->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_reports', function (Blueprint $table) {
            $table->dropForeign(['area_office_id']);
            $table->dropColumn('area_office_id');
            $table->dropForeign(['file_id']);
            $table->dropColumn('file_id');
            $table->dropColumn('x');
            $table->dropColumn('y');
            $table->dropColumn('qr_token');
        });
    }
}
