<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedToServiceQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_services', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
            $table->timestamp('approved_date')->nullable();
            $table->bigInteger('approved_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_services', function (Blueprint $table) {
            $table->dropColumn('service_quotesapproved');
            $table->dropColumn('approved_date');
            $table->dropColumn('approved_user');
        });
    }
}
