<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\CompanyPaymentType;

class PaymentTypeController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $payment_types = CompanyPaymentType::query();
        
        $payment_types->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $payment_types->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($payment_types->get());
	}

    public function store(Request $request, $company_id) {
        $payment_type_ids = array();
        foreach ($request->get('company_payment_types') as $key => $payment_type_arr)
        {
            if (isset($payment_type_arr['id'])) {
                $payment_type = CompanyPaymentType::find($payment_type_arr['id']);
            } else {
                $payment_type = new CompanyPaymentType;
                $payment_type->company_id = $company_id;
            }            
            $payment_type->label = $payment_type_arr['label'];
            $payment_type->save();

            $payment_type_ids[] = $payment_type->id;
        }

        $this->company->payment_types()->whereNotIn('id', $payment_type_ids)->whereNotIn('label', ['Paid'])->delete();

    	return response()->json($this->company->payment_types, 200);
    }
}