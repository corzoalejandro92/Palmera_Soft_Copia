<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePeriodicitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periodicities')->where('id',1)->update(['weeks'=> 1]);
        DB::table('periodicities')->where('id',2)->update(['weeks'=> 1]);
        DB::table('periodicities')->where('id',3)->update(['weeks'=> 1]);
        DB::table('periodicities')->where('id',4)->update(['weeks'=> 2]);
        DB::table('periodicities')->where('id',5)->update(['weeks'=> 4]);
        DB::table('periodicities')->where('id',6)->update(['weeks'=> 8]);
        DB::table('periodicities')->where('id',7)->update(['weeks'=> 13]);
        DB::table('periodicities')->where('id',8)->update(['weeks'=> 17]);
        DB::table('periodicities')->where('id',9)->update(['weeks'=> 21]);
        DB::table('periodicities')->where('id',10)->update(['weeks'=> 26]);
        DB::table('periodicities')->where('id',11)->update(['weeks'=> 52]);
        DB::table('periodicities')->where('id',12)->update(['weeks'=> 104]);
    }
}
