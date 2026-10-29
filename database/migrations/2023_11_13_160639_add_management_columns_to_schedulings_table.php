<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagementColumnsToSchedulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedulings', function (Blueprint $table) {
            $table->timestamp('start_management')->nullable()->after('headquarter_id');
            $table->timestamp('end_management')->nullable()->after('start_management');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedulings', function (Blueprint $table) {
            $table->dropColumn('start_management');
            $table->dropColumn('end_management');
        });
    }
}
