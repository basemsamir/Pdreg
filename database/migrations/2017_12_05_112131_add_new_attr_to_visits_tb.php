<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewAttrToVisitsTb extends Migration
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
            $table->string('ticket_status',1)->after('patient_id')->nullable();
            $table->string('watching_status')->after('ticket_type')->nullable();
            $table->string('sent_by_person',20)->after('watching_status')->nullable();
            $table->integer('file_type')->after('file_number')->nullable();
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
            $table->dropColumn(['ticket_status','watching_status','sent_by_person','file_type']);
        });
    }
}
