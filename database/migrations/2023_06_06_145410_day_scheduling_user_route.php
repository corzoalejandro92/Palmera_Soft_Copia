<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DaySchedulingUserRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP VIEW IF EXISTS `day_scheduling_user_route`");
        DB::unprepared("CREATE VIEW `day_scheduling_user_route` AS 
            SELECT su.scheduling_id, su.user_id, r.id route_id, DATE(r.date) FROM scheduling_user su
            INNER JOIN schedulings s ON su.scheduling_id = s.id
            INNER JOIN routes r on r.user_id = su.user_id AND DATE(r.date) = DATE(s.start)");
        return 0;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP VIEW IF EXISTS `day_scheduling_user_route`");
    }
}
