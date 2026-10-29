<?php

use Illuminate\Database\Seeder;
use App\FileType;

class TypeFileImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FileType::create([
            "name" => "Archivo de importación",
            "slug" => "import",
        ],
        [
            "name" => "Cotización",
            "slug" => "quote",
        ]
    );
    }
}
