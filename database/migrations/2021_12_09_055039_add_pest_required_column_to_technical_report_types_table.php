<?php

use Illuminate\Support\Facades\Schema;
use App\TechnicalReportType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPestRequiredColumnToTechnicalReportTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_report_types', function (Blueprint $table) {
            $table->boolean('pest_required')->default(0)->after('parent_id');
        });

        //ejecutar una actualizacion de registros
        TechnicalReportType::where('id',6)
        ->update(['pest_required'=>1],'picture_required', false, 'observation_required', false);

        TechnicalReportType::where('id',7)
        ->update(['name'=>'Consumo de cebo','picture_required'=> false, 'observation_required' => false]);

        TechnicalReportType::where('id',8)
        ->update(['name'=>'Pérdida de cebo','picture_required'=> false, 'observation_required' => false]);

        TechnicalReportType::where('id',9)
        ->update(['name'=>'Conteo','picture_required'=> false, 'observation_required' => false]);

        TechnicalReportType::where('id',10)
        ->update(['name'=>'Limpieza','picture_required'=> false, 'observation_required' => false]);

        TechnicalReportType::where('id',11)
        ->update(['name'=>'Vacía','picture_required'=> false, 'observation_required' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_report_types', function (Blueprint $table) {
            $table->dropColumn('pest_required');
        });
    }
}
