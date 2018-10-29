<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use App\Models\User;

class RetrofitRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrofit:role-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh user permissions as defined in: RolesAndPermissionsSeeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manage_users_permission = Permission::where('name', 'Can Manage Users')->first();
        if (!$manage_users_permission) {
            $manage_users_permission = Permission::create(['name' => 'Can Manage Users', 'slug' => 'manage.users']);
        }
        
        $manage_accounting_permission = Permission::where('name', 'Can Manage Accounting')->first();
        if (!$manage_accounting_permission) {
            $manage_accounting_permission = Permission::create(['name' => 'Can Manage Accounting', 'slug' => 'manage.accounting']);
        }
        
        $manage_materials_permission = Permission::where('name', 'Can Manage Materials')->first();
        if (!$manage_materials_permission) {
            $manage_materials_permission = Permission::create(['name' => 'Can Manage Materials', 'slug' => 'manage.users']);
        }
        
        $manage_phases_permission = Permission::where('name', 'Can Manage Phases')->first();
        if (!$manage_phases_permission) {
            $manage_phases_permission = Permission::create(['name' => 'Can Manage Phases', 'slug' => 'manage.phases']);
        }
        
        $manage_equipments_permission = Permission::where('name', 'Can Manage Equipments')->first();
        if (!$manage_equipments_permission) {
            $manage_equipments_permission = Permission::create(['name' => 'Can Manage Equipments', 'slug' => 'manage.eqipments']);
        }
        
        $manage_trades_permission = Permission::where('name', 'Can Manage Trades')->first();
        if (!$manage_trades_permission) {
            $manage_trades_permission = Permission::create(['name' => 'Can Manage Trades', 'slug' => 'manage.trades']);
        }
        
        $manage_statuses_permission = Permission::where('name', 'Can Manage Statuses')->first();
        if (!$manage_statuses_permission) {
            $manage_statuses_permission = Permission::create(['name' => 'Can Manage Statuses', 'slug' => 'manage.statuses']);
        }
        
        $add_projects_permission = Permission::where('name', 'Can Add Projects')->first();
        if (!$add_projects_permission) {
            $add_projects_permission = Permission::create(['name' => 'Can Add Projects', 'slug' => 'add.projects']);
        }
        
        $edit_projects_permission = Permission::where('name', 'Can Edit Projects')->first();
        if (!$edit_projects_permission) {
            $edit_projects_permission = Permission::create(['name' => 'Can Edit Projects', 'slug' => 'edit.projects']);
        }

        $delete_projects_permission = Permission::where('name', 'Can Delete Projects')->first();
        if (!$delete_projects_permission) {
            $delete_projects_permission = Permission::create(['name' => 'Can Delete Projects', 'slug' => 'delete.projects']);
        }
        
        $edit_companies_permission = Permission::where('name', 'Can Edit Companies')->first();
        if (!$edit_companies_permission) {
            $edit_companies_permission = Permission::create(['name' => 'Can Edit Companies', 'slug' => 'edit.companies']);
        }

        /**
         * Assign all permissions to admins
         */
        $admin_users = User::whereHas('roles', function($q){
                            $q->where('name', 'Admin');
                        })->get();

        foreach($admin_users as $user) {

            $user->syncPermissions([$manage_users_permission->id,
                                $manage_accounting_permission->id,
                                $manage_materials_permission->id,
                                $manage_phases_permission->id,
                                $manage_equipments_permission->id,
                                $manage_trades_permission->id,
                                $manage_statuses_permission->id,
                                $add_projects_permission->id,
                                $edit_projects_permission->id,
                                $delete_projects_permission->id,
                                $edit_companies_permission->id]);

        }

        $this->info('All done.');
    }
}
