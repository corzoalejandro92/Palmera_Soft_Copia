<?php

use Illuminate\Database\Seeder;
use App\PaperSize;

class QrsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaperSize::create([
            'name' => 'Carta',
            'slug' => 'letter'
        ]);

        PaperSize::create([
            'name' => 'Legal',
            'slug' => 'legal'
        ]);

        PaperSize::create([
            'name' => 'Tabloid',
            'slug' => 'tabloid'
        ]);

        PaperSize::create([
            'name' => 'A0 (841 x 1189 mm)',
            'slug' => 'A0'
        ]);
        PaperSize::create([
            'name' => 'A1 (594 x 841 mm)',
            'slug' => 'A1'
        ]);PaperSize::create([
            'name' => 'A2 (420 x 594 mm)',
            'slug' => 'A2'
        ]);
        PaperSize::create([
            'name' => 'A3 (297 x 420 mm)',
            'slug' => 'A3'
        ]);
        PaperSize::create([
            'name' => 'A4 (210 x 297 mm)',
            'slug' => 'A4'
        ]);
        PaperSize::create([
            'name' => 'A5 (148 x 210 mm)',
            'slug' => 'A5'
        ]);
        PaperSize::create([
            'name' => 'A6 (105 x 148 mm)',
            'slug' => 'A6'
        ]);
        PaperSize::create([
            'name' => 'A7 (74 x 105 mm)',
            'slug' => 'A7'
        ]);
        PaperSize::create([
            'name' => 'A8 (52 x 74 mm)',
            'slug' => 'A8'
        ]);

    }
}
