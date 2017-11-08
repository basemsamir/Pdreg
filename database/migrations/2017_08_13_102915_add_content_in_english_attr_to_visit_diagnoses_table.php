<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentInEnglishAttrToVisitDiagnosesTable extends Migration
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
			$table->text('content_in_english')->after('content')->nullable();
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
			$table->dropColumn(['content_in_english']);
        });
    }
}
