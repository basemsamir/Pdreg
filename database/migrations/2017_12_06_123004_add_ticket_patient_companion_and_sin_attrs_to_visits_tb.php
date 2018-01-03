<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTicketPatientCompanionAndSinAttrsToVisitsTb extends Migration
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
            $table->string('ticket_companion_name',50)->after('sent_by_person')->nullable();
            $table->bigInteger('ticket_companion_sin')->after('ticket_companion_name')->nullable();
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
            $table->dropColumn(['ticket_companion_name','ticket_companion_sin']);
        });
    }
}
