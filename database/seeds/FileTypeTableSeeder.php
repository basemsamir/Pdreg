<?php

use Illuminate\Database\Seeder;
use App\FileType;
class FileTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        FileType::create(['name'=>'عمليات']);
        FileType::create(['name'=>'باطنة']);
        FileType::create(['name'=>'خارجي']);
    }
}
