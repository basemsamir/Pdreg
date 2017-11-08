<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCureDiagnoseAttrVisitDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_diagnoses', function (Blueprint $table) {
            //
			$table->text('cure_description')->after('content_in_english')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_diagnoses', function (Blueprint $table) {
            //
			$table->dropColumn(['cure_description']);
        });
    }
}
