<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Relation;
class RelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
            $table->timestamps();
        });
		Relation::create(['name'=>'']);
		Relation::create(['name'=>'الأب']);
		Relation::create(['name'=>'الأم']);
		Relation::create(['name'=>'الزوجة']);
		Relation::create(['name'=>'الزوج']);
		Relation::create(['name'=>'الابن']);
		Relation::create(['name'=>'البنت']);
		Relation::create(['name'=>'الجد']);
		Relation::create(['name'=>'الجدة']);
		Relation::create(['name'=>'الاخ']);
		Relation::create(['name'=>'الاخت']);
		Relation::create(['name'=>'ابن الابن']);
		Relation::create(['name'=>'ابن الاخت']);
		Relation::create(['name'=>'العم']);
		Relation::create(['name'=>'العمة']);
		Relation::create(['name'=>'الخال']);
		Relation::create(['name'=>'الخالة']);
		Relation::create(['name'=>'ابن الاخ']);
		Relation::create(['name'=>'بنت الاخ']);
		Relation::create(['name'=>' ابن الاخت']);
		Relation::create(['name'=> 'ابن العم']);
		Relation::create(['name'=>'بنت العم']);
		Relation::create(['name'=>'ابن العمة']);
		Relation::create(['name'=>'بنت العمة']);
		Relation::create(['name'=>'ابن الخال']);
		Relation::create(['name'=>'بنت الخال']);
		Relation::create(['name'=>'ابن الخالة']);
		Relation::create(['name'=>'بنت الخالة']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('relations');
    }
}
