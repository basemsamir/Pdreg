<?php

use Illuminate\Database\Seeder;
use App\CureType;
class CureTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		CureType::truncate();
		CureType::create(['name'=>'مجاني']);
		CureType::create(['name'=>'تيسيري']);
		CureType::create(['name'=>'تعاقدات']);
		CureType::create(['name'=>'سداد']);
    }
}
