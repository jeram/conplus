<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\CompanyDepositType;

class DepositTypeController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $deposit_types = CompanyDepositType::query();
        
        $deposit_types->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $deposit_types->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($deposit_types->get());
	}

    public function store(Request $request, $company_id) {
        $deposit_type_ids = array();
        foreach ($request->get('company_deposit_types') as $key => $deposit_type_arr)
        {
            if (isset($deposit_type_arr['id'])) {
                $deposit_type = CompanyDepositType::find($deposit_type_arr['id']);
            } else {
                $deposit_type = new CompanyDepositType;
                $deposit_type->company_id = $company_id;
            }            
            $deposit_type->label = $deposit_type_arr['label'];
            $deposit_type->save();

            $deposit_type_ids[] = $deposit_type->id;
        }

        $this->company->deposit_types()->whereNotIn('id', $deposit_type_ids)->delete();

    	return response()->json($this->company->deposit_types, 200);
    }
}