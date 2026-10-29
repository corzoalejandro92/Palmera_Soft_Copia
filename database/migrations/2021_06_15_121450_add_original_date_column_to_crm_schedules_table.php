<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOriginalDateColumnToCrmSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->date('original_date')->after('date')->nullable()->comment('Esta fecha será el control para editar el cronograma sin perder los cambios realizados en el');
        });

        DB::update('update crm_schedules set original_date = date where original_date is null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->dropColumn('original_date');
        });
    }
}
