<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Company\PostRequest;
use App\Http\Requests\Api\Company\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Company::class);
    }

    public function index(Request $request, $company_id) {        

        /*$this->authorize('index', Company::class);

        $companies = new Company;
        
        $companies->where('company_id', $this->company->id);

        $companies->with('details');

        // set up search parameters.
        if ($request->filled('name')) {
            $companies->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        return response()->json($companies);*/
	}

    public function show(Company $company) {
    	return response()->json($company);
    }

    public function store(PostRequest $request, $company_id) {
    	//create company here
    	$company = new Company;
    	$company->name = $request->get('name');
    	$company->cost = $request->get('cost');
    	$company->company_id = $company_id;
    	$company->save();

    	return response()->json($company, 200);
    }

    public function update(PatchRequest $request, Company $company) {
    	// updating a company here
    	if ($request->filled('name')) {
            $company->name = $request->get('name');
        }

        if ($request->filled('cost')) {
            $company->cost = $request->get('cost');
        }

    	$company->save();

    	return response()->json($company);
    }

    public function destroy(Company $company) {
    	$company->delete();

        return response()->json([], 202);
    }

    public function restore(Company $company) {
    	$company = Company::withTrashed()->findOrFail($company_id);
        $this->authorize('restore', $company);
        $company->restore();

        return response()->json($company);
    }

    public function toggleActive($company_id, Company $company) {

    }
}