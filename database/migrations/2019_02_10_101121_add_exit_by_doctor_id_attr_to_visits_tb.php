<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExitByDoctorIdAttrToVisitsTb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
            $table->integer('closed_by_doctor_id')->default(0)->after('closed');
            $table->date('closed_date')->nullable()->after('closed_by_doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
            $table->dropColumn('closed_by_doctor_id','closed_date');
        });
    }
}
