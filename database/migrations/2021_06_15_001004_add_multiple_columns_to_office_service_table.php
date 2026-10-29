<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToOfficeServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_service', function (Blueprint $table) {
            $table->string('time', 8)->nullable()->after('service_id');
            $table->json('technicals')->nullable()->after('technical_quantity');
            $table->date('start')->nullable()->after('technicals');
            $table->date('end')->nullable()->after('start');
            $table->integer('init_status')->default(1)->after('end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_service', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('technicals');
            $table->dropColumn('start');
            $table->dropColumn('end');
            $table->dropColumn('init_status');

        });
    }
}
