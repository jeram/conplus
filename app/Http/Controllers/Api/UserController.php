<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {

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
        return Auth::user()->companies()->with('projects')->get();
    }
}
