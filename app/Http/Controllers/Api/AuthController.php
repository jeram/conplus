<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Company;

class AuthController extends Controller
{
    protected $user;
    protected $company;

    public function __construct()
    {
        $this->user = \Auth::guard('api')->user();
        //$this->user = \Auth::user();

        $this->company = null;

        if (!empty($this->user)) {
            $company_id = request()->segment('3', null);
            $this->company = $this->user->companies()->findOrFail($company_id);
        }
    }
}
