<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\MedicalUnit;

class CreateMedicalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_units', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('type',1);
            $table->timestamps();
        });
		MedicalUnit::create(['name'=>'الجلدية','type'=>'d']);
		MedicalUnit::create(['name'=>'العظام','type'=>'d']);
		MedicalUnit::create(['name'=>'أنف و أذن و حنجرة','type'=>'d']);
		MedicalUnit::create(['name'=>'القلب','type'=>'d']);
		MedicalUnit::create(['name'=>'الجراحة العامة','type'=>'d']);
		MedicalUnit::create(['name'=>'جراحة المخ و الأعصاب','type'=>'d']);
		MedicalUnit::create(['name'=>'جراحة الشرايين','type'=>'d']);
		MedicalUnit::create(['name'=>'جراحة التجميل','type'=>'d']);
		MedicalUnit::create(['name'=>'جراحة الصدر و القلب','type'=>'d']);
		MedicalUnit::create(['name'=>'طب و جراحة العيون','type'=>'d']);
		MedicalUnit::create(['name'=>'المناطق الحارة و الجهاز الهضمي','type'=>'d']);
		MedicalUnit::create(['name'=>'الصدرية','type'=>'d']);
		MedicalUnit::create(['name'=>'الأورام','type'=>'d']);
		MedicalUnit::create(['name'=>'العناية العامة','type'=>'d']);
		MedicalUnit::create(['name'=>'الأمراض العصبية','type'=>'d']);
		MedicalUnit::create(['name'=>'الأمراض النفسية','type'=>'d']);
		MedicalUnit::create(['name'=>'عناية ما بعد العمليات','type'=>'d']);
		MedicalUnit::create(['name'=>'أمراض الباطنة العامة','type'=>'d']);
		MedicalUnit::create(['name'=>'الروماتيزم و التأهيل','type'=>'d']);
		MedicalUnit::create(['name'=>'الأصابات','type'=>'d']);
		MedicalUnit::create(['name'=>'الحروق','type'=>'d']);
		MedicalUnit::create(['name'=>'طب القلب و الأوعية الدموية','type'=>'d']);
		MedicalUnit::create(['name'=>'الأستقبال العام','type'=>'d']);
		MedicalUnit::create(['name'=>'الأستقبال العام 2','type'=>'d']);
		MedicalUnit::create(['name'=>'الألام المزمنة','type'=>'d']);
		MedicalUnit::create(['name'=>'المسالك البولية','type'=>'d']);
		
		Schema::create('medical_unit_visit', function (Blueprint $table) {
            $table->integer('visit_id')->unsigned()->index();
            $table->foreign('visit_id')->references('id')->on('visits');
            $table->integer('medical_unit_id')->unsigned()->index();
            $table->foreign('medical_unit_id')->references('id')->on('medical_units');
			$table->integer('user_id');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('medical_unit_visit');
        Schema::drop('medical_units');
    }
}
