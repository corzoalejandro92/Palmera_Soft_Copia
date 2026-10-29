<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('consecutive')->nullable();
            $table->bigInteger('commercial_id')->unsigned()->nullable();
            $table->foreign('commercial_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('possible_date')->nullable();
            $table->boolean('diagnostic')->default(0);
            $table->bigInteger('coordinator_id')->unsigned()->nullable();
            $table->foreign('coordinator_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('has_discount')->default(0);
            $table->float('discount_percent',3,1)->nullable();
            $table->decimal('discount_value',18,0)->nullable();
            $table->decimal('subtotal',18,0)->default(0);
            $table->decimal('total',18,0)->default(0);
            $table->timestamp('expire_date')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on('quote_status')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('format_id')->unsigned()->nullable();
            $table->foreign('format_id')->references('id')->on('quote_templates')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->longText('format_html')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quote_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quote_sends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('email');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('periodicities',function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('value');
            $table->enum('unit',['day','month','year','never'])->default('day');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('time_units', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('seconds')->default('60');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('quote_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('coverage_id')->unsigned()->nullable();
            $table->foreign('coverage_id')->references('id')->on('detail_services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('coverage')->nullable();
            $table->integer('visits')->default(0);
            $table->decimal('price',18,0)->default(0);
            $table->integer('time_value')->nullable();
            $table->integer('time_unit_id')->nullable()->unsigned();
            $table->foreign('time_unit_id')->on('time_units')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('periodicity_id')->unsigned()->nullable();
            $table->foreign('periodicity_id')->on('periodicities')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->text('observation')->nullable();
            $table->boolean('genuine_service')->default(1);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quote_service_infestations', function (Blueprint $table) { 
            $table->bigIncrements('id');
            $table->bigInteger('infestation_id')->unsigned()->nullable();
            $table->foreign('infestation_id')->references('id')->on('infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('quote_service_id')->unsigned()->nullable();
            $table->foreign('quote_service_id')->references('id')->on('quote_services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });

        Schema::create('area_customer_quote_service_infestation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('area_customer_id')->unsigned()->nullable();
            $table->foreign('area_customer_id')->references('id')->on('area_customers')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('quote_service_infestation_id')->unsigned()->nullable();
            $table->foreign('quote_service_infestation_id', 'acqsinfestation1_id_foreign')->references('id')->on('quote_service_infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });

        Schema::create('quote_service_infestation_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('productable','acqsiproducts_id');
            $table->bigInteger('quote_service_infestation_id')->unsigned()->nullable();
            $table->foreign('quote_service_infestation_id', 'acqsinfestation2_id_foreign')->references('id')->on('quote_service_infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->enum('type',['supply','application'])->default('supply');
            $table->integer('quantity')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('crm_tracking_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('crm_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('crm_tracking_type_id')->unsigned()->nullable();
            $table->foreign('crm_tracking_type_id')->references('id')->on('crm_tracking_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->nullableMorphs('trackable');
            $table->timestamp('date')->nullable();
            $table->text('title');
            $table->text('detail');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('system')->default(1);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('document_quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('crm_trackings');
        Schema::dropIfExists('crm_tracking_types');
        Schema::dropIfExists('quote_service_infestation');
        Schema::dropIfExists('periodicities');
        Schema::dropIfExists('quote_services');
        Schema::dropIfExists('quote_observations');
        Schema::dropIfExists('quote_sends');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('quote_status');
        Schema::dropIfExists('document_quotes');
    }
}
