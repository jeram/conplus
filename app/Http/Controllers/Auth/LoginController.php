<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Support\Facades\Auth;

use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'redirectLogout']);
    }
	
	/*protected function authenticated($request, $user) {
		
		$user->rollApiKey();
		
		if ($user->hasRole('developer')) {
			return redirect('/developer');
		} else {
			return redirect('/');
		}
		
	}*/
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        //die('loginform');
        return view('auth.login');
    }
    
    /**
     * Use to initiate and validate the login process
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        //attempt to login
        $valid = Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]);
        
        if ($valid) {
            $data = [];
            $meta = [];

            $data['name'] = Auth::user()->name;
            $data['msg'] = 'You have successfully logged in.';
            $meta['token'] = Auth::user()->api_token;

            return response()->json([
                'data' => $data,
                'meta' => $meta
            ]);

        }
        // default is unauthed.
        return response()->json([
            'error' => 'Authentication failed.',
        ], 401);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json([
            'data' => 'You have successfully logged out.',
        ]);
    }
    
    public function redirectLogout()
    {
        Auth::logout();
        
        return redirect('/');
    }

    public function getAuthUser()
    {
        $user = $this->guard()->user();

        if ($user) {
            $user->agency_name = $user->agency->name;
            $user->single_company_mode = $user->agency->single_company_mode;
            $user->makeVisible('agency_name')->makeVisible('single_company_mode');
            return response()->json(compact('user'));
        }
        // default is unauthed.
        return response()->json([
            'error' => 'Authenticated failed.',
        ], 401);
    }

    public function updateAuthUser(UpdateProfileRequest $request)
    {
        // saving user info
        $user = $this->guard()->user();
        $user->phone_number = $request->phone_number;
        $user->two_factor = $request->two_factor;
        $user->save();

        return response()->json($user);
    }

    public function updateUserPassword(UpdatePasswordRequest $request)
    {
        // saving user info
        $user = $this->guard()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json($user);
    }
	
}
