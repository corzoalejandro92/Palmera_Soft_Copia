<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToUserTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_trackings', function (Blueprint $table) {
            $table->longText('observation_receives')->nullable()->after('observation');
            $table->string('name_receives',150)->nullable()->after('observation_receives');
            $table->string('cc_receives',20)->nullable()->after('name_receives');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_trackings', function (Blueprint $table) {
            $table->dropColumn('observation_receives');
            $table->dropColumn('name_receives');
            $table->dropColumn('cc_receives');
        });
    }
}
