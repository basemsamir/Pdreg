<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoAttributesEntryDescAndTimeVisitTable extends Migration
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
			$table->text('entry_reason_desc')->after('phone_num')->nullable();
			$table->string('entry_time',10)->after('entry_reason_desc')->nullable();
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
			$table->dropColumn('entry_reason_desc');
			$table->dropColumn('entry_time');
        });
    }
}
