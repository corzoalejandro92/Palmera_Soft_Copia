<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTicketHasRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_ticket_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('category_ticket_id');
            $table->unsignedBigInteger('rol_id');
            $table->foreign('category_ticket_id')->references('id')->on('category_tickets');
            $table->foreign('rol_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_ticket_has_roles');
    }
}
