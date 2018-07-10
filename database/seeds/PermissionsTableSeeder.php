<?php

use App\Models\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    /**
	     * Add Permissions
	     *
	     */
        if (Permission::where('name', '=', 'Can Manage Users')->first() === null) {
			Permission::create([
			    'name' => 'Can Manage Users',
			    'slug' => 'manage.users',
			    'description' => 'Can manage users',
			    'model' => 'Permission',
			]);
        }

        if (Permission::where('name', '=', 'Can Manage Accounting')->first() === null) {
			Permission::create([
			    'name' => 'Can Manage Accounting',
			    'slug' => 'manage.accounting',
			    'description' => 'Can Manage Accounting',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Manage Materials')->first() === null) {
			Permission::create([
			    'name' => 'Can Manage Materials',
			    'slug' => 'manage.materials',
			    'description' => 'Can Manage Materials',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Manage Equipments')->first() === null) {
			Permission::create([
			    'name' => 'Can Manage Equipments',
			    'slug' => 'manage.equipments',
			    'description' => 'Can Manage Equipments',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Manage Customers')->first() === null) {
			Permission::create([
			    'name' => 'Can Manage Customers',
			    'slug' => 'manage.customers',
			    'description' => 'Can Manage Customers',
			    'model' => 'Permission',
			]);
        }
		
		#Projects
        if (Permission::where('name', '=', 'Can Add Projects')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Projects',
			    'slug' => 'add.projects',
			    'description' => 'Can Add Projects',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Projects')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Projects',
			    'slug' => 'edit.projects',
			    'description' => 'Can Edit Projects',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Projects')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Projects',
			    'slug' => 'delete.projects',
			    'description' => 'Can Delete Projects',
			    'model' => 'Permission',
			]);
        }
		
		#Companies
        if (Permission::where('name', '=', 'Can Add Companies')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Companies',
			    'slug' => 'add.companies',
			    'description' => 'Can Add Companies',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Companies')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Companies',
			    'slug' => 'edit.companies',
			    'description' => 'Can Edit Companies',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Companies')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Companies',
			    'slug' => 'delete.companies',
			    'description' => 'Can Delete Companies',
			    'model' => 'Permission',
			]);
        }
		
		#Attachments
		if (Permission::where('name', '=', 'Can Add Attachments')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Attachments',
			    'slug' => 'add.attachments',
			    'description' => 'Can Add Attachments',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Attachments')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Attachments',
			    'slug' => 'edit.attachments',
			    'description' => 'Can Edit Attachments',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Attachments')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Attachments',
			    'slug' => 'delete.attachments',
			    'description' => 'Can Delete Attachments',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can View Attachments')->first() === null) {
			Permission::create([
			    'name' => 'Can View Attachments',
			    'slug' => 'view.attachments',
			    'description' => 'Can View Attachments',
			    'model' => 'Permission',
			]);
        }
		
		#Notes
		if (Permission::where('name', '=', 'Can Add Notes')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Notes',
			    'slug' => 'add.notes',
			    'description' => 'Can Add Notes',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Notes')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Notes',
			    'slug' => 'edit.notes',
			    'description' => 'Can Edit Notes',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Notes')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Notes',
			    'slug' => 'delete.notes',
			    'description' => 'Can Delete Notes',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can View Notes')->first() === null) {
			Permission::create([
			    'name' => 'Can View Notes',
			    'slug' => 'view.notes',
			    'description' => 'Can View Notes',
			    'model' => 'Permission',
			]);
        }
		
		#Phases
		if (Permission::where('name', '=', 'Can Add Phases')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Phases',
			    'slug' => 'add.phases',
			    'description' => 'Can Add Phases',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Phases')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Phases',
			    'slug' => 'edit.phases',
			    'description' => 'Can Edit Phases',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Phases')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Phases',
			    'slug' => 'delete.phases',
			    'description' => 'Can Delete Phases',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can View Phases')->first() === null) {
			Permission::create([
			    'name' => 'Can View Phases',
			    'slug' => 'view.phases',
			    'description' => 'Can View Phases',
			    'model' => 'Permission',
			]);
        }
		
		#Schedules
		if (Permission::where('name', '=', 'Can Add Schedules')->first() === null) {
			Permission::create([
			    'name' => 'Can Add Schedules',
			    'slug' => 'add.schedules',
			    'description' => 'Can Add Schedules',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Edit Schedules')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Schedules',
			    'slug' => 'edit.schedules',
			    'description' => 'Can Edit Schedules',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Delete Schedules')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Schedules',
			    'slug' => 'delete.schedules',
			    'description' => 'Can Delete Schedules',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can View Schedules')->first() === null) {
			Permission::create([
			    'name' => 'Can View Schedules',
			    'slug' => 'view.schedules',
			    'description' => 'Can View Schedules',
			    'model' => 'Permission',
			]);
        }
    }
}
