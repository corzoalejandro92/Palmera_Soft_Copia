<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateCategoryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug',100)->unique();
            $table->string('name');
            $table->string('class',100);
            $table->timestamps();
        });
        Artisan::call('db:seed',['--class'=>'CategoryTicketSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_tickets');
    }
}
