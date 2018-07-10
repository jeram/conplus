<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
			return response()->json(compact('user'));
        }
        // default is unauthed.
        return response()->json([
            'error' => 'Authentication failed.',
        ], 401);
    }
	
	public function home()
    {
        return view('home');
    }
}
