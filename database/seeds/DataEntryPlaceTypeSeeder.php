<?php

use Illuminate\Database\Seeder;
use App\DataEntryPlaceType;

class DataEntryPlaceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DataEntryPlaceType::create(['name'=>'مكتب حجز التذاكر']);
		DataEntryPlaceType::create(['name'=>'مكتب الدخول']);
    }
}
