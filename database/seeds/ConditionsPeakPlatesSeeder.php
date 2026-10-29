<?php

use App\ConditionsPeakPlates;
use Illuminate\Database\Seeder;

class ConditionsPeakPlatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConditionsPeakPlates::create([
            'slug' => 'first.number',
            'name' => 'Primer numero'
        ]);

        ConditionsPeakPlates::create([
            'slug' => 'last.number',
            'name' => 'Ultimo numero'
        ]);
    }
}
