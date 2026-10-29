<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->longText('key')->nullable();
            $table->longText('base64_logo')->nullable();
            $table->boolean('is_backend')->default(true);
            $table->boolean('enabled')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Artisan::call('db:seed',['--class'=>'IntegrationsTableSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integrations');
    }
}
