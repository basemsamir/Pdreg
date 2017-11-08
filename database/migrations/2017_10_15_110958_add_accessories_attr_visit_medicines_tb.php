<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessoriesAttrVisitMedicinesTb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_medicines', function (Blueprint $table) {
            //
			$table->text('accessories')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_medicines', function (Blueprint $table) {
            //
			$table->dropColumn(['accessories']);
        });
    }
}
