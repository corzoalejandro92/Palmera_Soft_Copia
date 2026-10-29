<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mechanisms', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name')->unique()->nullable();
            $table->string('avatar',200)->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('avatar',200)->nullable();
            $table->string('name')->unique();
            $table->string('code')->unique()->nullable();
            $table->boolean('enabled')->default(0);
            $table->string('description',500)->nullable();
            $table->bigInteger('unit_id')->nullable()->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE');
            $table->bigInteger('application_id')->nullable()->unsigned();
            $table->foreign('application_id')->references('id')->on('applications')->onUpdate('CASCADE');
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('CASCADE');
            $table->bigInteger('mechanism_id')->nullable()->unsigned();
            $table->foreign('mechanism_id')->references('id')->on('mechanisms')->onUpdate('CASCADE');
            $table->boolean('toxico')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('product_service', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });


        Schema::create('infestation_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('infestation_id')->nullable()->unsigned();
            $table->foreign('infestation_id')->references('id')->on('infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });

        Schema::create('detail_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('mechanisms');
        Schema::dropIfExists('product_service');
        Schema::dropIfExists('infestation_product');
        Schema::dropIfExists('detail_products');
    }
}
