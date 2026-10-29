<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStatusIdColumnToRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->bigInteger('status_id')->after('user_id')->unsigned()->nullable()->deafult(1);
            $table->foreign('status_id')->on('route_status')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

                    //actualizar los estados que estén nulos a init
                    DB::update('update routes set status_id = 1 where status_id is null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
}
