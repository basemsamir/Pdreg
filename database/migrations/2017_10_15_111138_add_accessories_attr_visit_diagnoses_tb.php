<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessoriesAttrVisitDiagnosesTb extends Migration
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
			$table->text('accessories')->after('cure_description')->nullable();
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
			$table->dropColumn(['accessories']);
        });
    }
}
