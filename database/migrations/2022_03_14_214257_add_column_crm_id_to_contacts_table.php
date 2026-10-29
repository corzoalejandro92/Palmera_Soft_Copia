<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCrmIdToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->bigInteger('crm_id')->unsigned()->nullable()->after('id');
            $table->foreign('crm_id')->on('crms')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

        //ejecutar update para actualizar los registros exitentes
        $contacts = \App\Contact::whereHas('offices')->get();
        if(!is_null($contacts)){
            foreach ($contacts as $cid => $contact) {
                $crm_id = $contact->offices->last()->crm_id;
                $contact->crm_id = $crm_id;
                $contact->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['crm_id']);
            $table->column('crm_id');
        });
    }
}
