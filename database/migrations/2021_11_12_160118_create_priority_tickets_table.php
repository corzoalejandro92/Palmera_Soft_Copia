<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreatePriorityTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priority_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug',100)->unique();
            $table->string('name');
            $table->string('full_name');
            $table->string('class',100);
            $table->timestamps();
        });
        Artisan::call('db:seed',['--class'=>'PriorityTicketSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('priority_tickets');
    }
}
