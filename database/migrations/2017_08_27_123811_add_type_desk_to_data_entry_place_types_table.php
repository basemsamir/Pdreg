<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeDeskToDataEntryPlaceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrypoints', function (Blueprint $table) {
            //
			$table->string('sub_type',15)->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entrypoints', function (Blueprint $table) {
            //
			$table->dropColumn(['sub_type']);
        });
    }
}
