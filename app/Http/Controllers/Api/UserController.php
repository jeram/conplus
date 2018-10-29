<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Http\Requests\Api\User\PostRequest;
use App\Http\Requests\Api\User\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id)
    {
        $export_type = $request->get('export_type', 'json');

        $users = User::query();

        // filter only users for the current company
        $users->whereHas('companies', function($query) use ($company_id) {
            $query->where('companies.id', $company_id);
        });

        // set up search parameters.
        if ($request->filled('name')) {
            $users->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }
        if ($request->filled('email')) {
            $users->where('email', 'LIKE', '%' . $request->get('email') . '%');
        }

        $users->with('permissions');
        $users->with('roles');

        // search
        if ($request->filled('q')) {
            $users->where('name', 'LIKE', '%' . $request->get('q') . '%');
            $users->orWhere('email', 'LIKE', '%' . $request->get('q') . '%');
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $users);
                break;
            }
            case 'json': {
                return response()->json($users->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, $company_id)
    {        
        $user = new User;
        $user->name                     = $request->get('name');
        $user->email                    = $request->get('email');
        $user->password                 = bcrypt($this->generate_password(6));
        
        $user->save();

        if ($request->get('role') == 'User') {
            // detach admin role
            $user->detachRole('Admin');

            $userRole 		= Role::where('name', '=', 'User')->first();
            $user->attachRole($userRole);

            //assign the selected permissions
            $user->syncPermissions($request->get('permissions'));

        } elseif($request->get('role') == 'Admin') {
            // detach user role
            $user->detachRole('User');

            $adminRole 			= Role::where('name', '=', 'Admin')->first();
            $user->attachRole($adminRole);

            // assign all permissions
            $permissions 		= Permission::all();
            $user->syncPermissions($permissions);

        }
        
        // assign to company
        $user->companies()->sync([$company_id]);
        //email the ne user


        return response()->json($user, 200);
    }

    private function generate_password($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $project_id, User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\User $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $user_id)
    {
        $user = User::find($user_id);
        
        if ($request->filled('name')) {
            $user->name                     = $request->get('name');
        }
        if ($request->filled('email')) {
            $user->email                    = $request->get('email');
        }
        if ($request->filled('new_password')) {
            $user->password                 = bcrypt($request->get('new_password'));
        }
        $user->save();

        if ($request->get('role') == 'User') {
            // detach admin role
            $user->detachRole('Admin');

            $userRole 		= Role::where('name', '=', 'User')->first();
            $user->attachRole($userRole);

            //assign the selected permissions
            $user->syncPermissions($request->get('permissions'));

        } elseif($request->get('role') == 'Admin') {
            // detach user role
            $user->detachRole('User');

            $adminRole 			= Role::where('name', '=', 'Admin')->first();
            $user->attachRole($adminRole);

            // assign all permissions
            $permissions 		= Permission::all();
            $user->syncPermissions($permissions);

        }
        /*if ($request->filled('permissions')) {
            $user->syncPermissions($request->get('permissions'));
        }*/
       
        return response()->json($user, 200);
    }

    public function reset_password(PatchRequest $request, $company_id, $user_id)
    {
        $user = User::find($user_id);
        
        $user->password = bcrypt($this->generate_password(6));

        $user->save();

        //email the user with new password

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $user_id)
    {
        User::find($user_id)->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->paginate(10);

        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'data' => $data,
        ];
        
        return response()->json($response);
    }

    public function auth() {

        if (Auth::check()) {
            $user = Auth::user();
            
            $user->makeVisible('companies');
            //$user->companies = $user->companies;
            return response()->json(compact('user'));
        }
        // default is unauthed.
        return response()->json([
            'error' => 'Authenticated failed.',
        ], 401);
        
    }
    
    public function getCompanies() {
        
        return response()->json(Auth::user()->companies()->with('active_projects')->get());

    }
}
