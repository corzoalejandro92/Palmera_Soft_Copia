<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeedbackColumnToCrmScheduleObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_schedule_observations', function (Blueprint $table) {
            $table->longText('feedback')->nullable();
            $table->timestamp('feedback_at')->nullable();
            $table->bigInteger('feedback_by')->nullable()->unsigned();
            $table->boolean('private')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_schedule_observations', function (Blueprint $table) {
            $table->dropColumn('feedback');
            $table->dropColumn('feedback_at');
            $table->dropColumn('feedback_by');
            $table->dropColumn('private');
        });
    }
}
