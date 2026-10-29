<?php

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeBodegaToFoxconDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Type::hasType('enum') ? Type::hasType('enum') : Type::addType('enum', StringType::class);
        
        Schema::table('foxcon_document_details', function (Blueprint $table) {
            $table->string('bodega', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foxcon_document_details', function (Blueprint $table) {
            DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            Type::hasType('enum') ? Type::hasType('enum') : Type::addType('enum', StringType::class);
            $table->string('bodega', 4)->nullable()->change();

        });
    }
}
