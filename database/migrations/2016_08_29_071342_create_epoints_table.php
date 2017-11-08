<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entrypoint;

class CreateEpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrypoints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->string('location');
			$table->timestamps();
        });
		Schema::create('entrypoint_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('entrypoint_id')->unsigned()->index();
            $table->foreign('entrypoint_id')->references('id')->on('entrypoints');
			$table->timestamps();
        });
		Entrypoint::create(['name'=>'„ﬂ » œŒÊ· «·⁄Ì«œ« ','location'=>'„»‰Ï «·⁄Ì«œ« ']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		
		Schema::drop('entrypoint_user');
        Schema::drop('entrypoints');
      
    }
}
