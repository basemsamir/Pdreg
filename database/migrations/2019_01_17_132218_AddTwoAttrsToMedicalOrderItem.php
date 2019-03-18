<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoAttrsToMedicalOrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_order_items', function (Blueprint $table) {
            $table->string('proc_name', 255)->default('')->after('proc_id');
            $table->boolean('old_format')->default(false)->after('proc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_order_items', function (Blueprint $table) {
            //
            $table->dropColumn(['proc_name','old_format']);
        });
    }
}
