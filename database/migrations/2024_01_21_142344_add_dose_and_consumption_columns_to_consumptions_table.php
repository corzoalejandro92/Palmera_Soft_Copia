<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoseAndConsumptionColumnsToConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consumptions', function (Blueprint $table) {
            $table->double('dose')->after('quantity')->nullable()->default(0);
            $table->double('consumption')->after('dose')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consumptions', function (Blueprint $table) {
            $table->dropColumn('dose');
            $table->dropColumn('consumption');
        });
    }
}
