<?php

use App\Models\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $developerRole 		= Role::where('name', '=', 'Developer')->first();
        $adminRole 			= Role::where('name', '=', 'Admin')->first();
		$permissions 		= Permission::all();

	    /**
	     * Add Users
	     *
	     */
        if (User::where('email', '=', 'developer@developer.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Developer',
	            'email' => 'developer@developer.com',
	            'password' => bcrypt('conplusdeveloper'),
	        ]);

	        $newUser->attachRole($developerRole);
			foreach ($permissions as $permission) {
				$newUser->attachPermission($permission);
			}

        }

        if (User::where('email', '=', 'admin@admin.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Admin',
	            'email' => 'admin@admin.com',
	            'password' => bcrypt('conplusadmin'),
	        ]);
	        
	        $newUser->attachRole($adminRole);
			foreach ($permissions as $permission) {
				$newUser->attachPermission($permission);
			}

        }

    }
}