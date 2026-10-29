<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiplesColumsToCrmSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->string('start_hour', 8)->nullable();
            $table->boolean('enable_fix_weeks')->default(0);
            $table->boolean('use_fifth_month_week')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->dropColumn('use_fifth_month_week');
            $table->dropColumn('enable_fix_weeks');
            $table->dropColumn('start_hour');
        });
    }
}
