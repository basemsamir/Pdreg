<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcedureRisIdToProcedureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            //
			$table->text('proc_ris_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedures', function (Blueprint $table) {
            //
			$table->dropColumn('proc_ris_id');
        });
    }
}
