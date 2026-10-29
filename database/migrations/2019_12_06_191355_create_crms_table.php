<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('mean_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('crms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type_customer')->nullable()->unsigned();
            $table->string('avatar',200)->nullable();
            $table->string('document')->nullable();
            $table->string('name')->unique();
            $table->bigInteger('crm_type_id')->nullable()->unsigned();
            $table->foreign('crm_type_id')->on('crm_types')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('mean_contact_id')->nullable()->unsigned();
            $table->foreign('mean_contact_id')->on('mean_contacts')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->boolean('customer_national')->nullable();
            $table->string('periodicity')->default(30);
            $table->string('billing_cycle')->default(15);
            $table->boolean('pay_method')->default(0);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contact_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique(); 
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });


        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('email')->nullable(); 
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contact_contact_type', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('contact_id')->nullable()->unsigned();
            $table->foreign('contact_id')->on('contacts')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('contact_type_id')->nullable()->unsigned();
            $table->foreign('contact_type_id')->on('contact_types')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('address');
            $table->text('observation')->nullable();
            $table->text('lat');
            $table->text('lng');
            $table->integer('headquarter_id')->unsigned()->nullable();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contact_office', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contact_id')->nullable()->unsigned();
            $table->foreign('contact_id')->on('contacts')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamps();
        });


        Schema::create('crm_user',function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('area_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('area_customers');
        Schema::dropIfExists('crm_user');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('crms');
        Schema::dropIfExists('means_contacts');
        Schema::dropIfExists('crm_types');
        Schema::dropIfExists('contact_office');
        Schema::dropIfExists('contact_types');
    }
}
