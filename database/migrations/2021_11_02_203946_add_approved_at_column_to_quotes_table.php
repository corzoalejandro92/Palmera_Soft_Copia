<?php

use App\Quote;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddApprovedAtColumnToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table)
        {
            $table->timestamp('approved_at')->nullable()->default(null)->after('updated_at');
        });
        
        Quote::withTrashed()
            ->whereIn('status_id',[2,4,5,6])
            ->update([
                "approved_at" => DB::raw("`updated_at`"),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
}
