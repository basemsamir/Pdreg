<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Wsconfig;
class CreateWebServiceConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsconfig', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url');
            $table->string('sending_app',30);
            $table->string('sending_fac',30);
            $table->string('receiving_app',30);
            $table->string('receiving_fac',30);
            $table->timestamps();
        });
		
		Wsconfig::create(['url'=>'http://172.16.0.118:2886/AUNHHL7?wsdl',
						  'sending_app'=>'AUNHHIS',
						  'sending_fac'=>'AUNH',
						  'receiving_app'=>'',
						  'receiving_fac'=>'']);
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wsconfig');
    }
}
