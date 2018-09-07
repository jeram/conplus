<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id)
    {
        $permissions = UserPermission::query();
        $permissions->whereNotIn('slug', 
                [
                    'manage.accounting',
                    'add.accounting',
                    'edit.accounting',
                    'delete.accounting',
                    'view.accounting',

                    'manage.customers',
                    'add.customers',
                    'edit.customers',
                    'delete.customers',
                    'view.customers',

                    'manage.companies',
                    'add.companies',
                    'edit.companies',
                    'delete.companies',
                    'view.companies',

                    'manage.schedules',
                    'add.schedules',
                    'edit.schedules',
                    'delete.schedules',
                    'view.schedules',

                    'manage.attachments',
                    'add.attachments',
                    'edit.attachments',
                    'delete.attachments',
                    'view.attachments',

                    'manage.notes',
                    'add.notes',
                    'edit.notes',
                    'delete.notes',
                    'view.notes',
                ]);
        
        return response()->json($permissions->get());
                
    }
}
