<?php

use Illuminate\Database\Seeder;
use App\Role;

class AddNewRoleAndUpdateRoleNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$role=Role::find(2);
		$role->arabic_name='الطبيب';
		$role->save();
		$role=Role::find(3);
		$role->arabic_name='التمريض';
		$role->save();
		$role=Role::find(4);
		$role->arabic_name='موظف مكتب دخول';
		$role->save();
		$role=Role::find(5);
		$role->arabic_name='موظف مكتب حجز تذاكر العيادات';
		$role->save();
		$role=Role::find(6);
		$role->arabic_name='مسئول عن النظام';
		$role->save();
		
		Role::create([
			'name'=>'Desk',
			'arabic_name'=>'موظف مكتب استقبال'
		
		]);
		
    }
}
