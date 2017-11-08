<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoAttrToVisitsTable extends Migration
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
			$table->integer('convert_to_entry_id')->after('user_id')->nullable();
			$table->integer('convert_to_user_id')->after('convert_to_entry_id')->nullable();
			$table->integer('converted_by')->after('convert_to_user_id')->nullable();
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
			$table->dropColumn(['convert_to_entry_id','convert_to_user_id','converted_by']);
        });
    }
}
