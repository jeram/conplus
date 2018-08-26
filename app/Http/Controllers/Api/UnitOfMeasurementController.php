<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $units = UnitOfMeasurement::query();
        
        $units->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $units->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($units->get());
	}

    public function store(Request $request, $company_id) {
        $unit_ids = array();
        foreach ($request->get('unit_of_measurements') as $key => $unit_arr)
        {
            if (isset($unit_arr['id'])) {
                $unit = UnitOfMeasurement::find($unit_arr['id']);
            } else {
                $unit = new UnitOfMeasurement;
                $unit->company_id = $company_id;
            }            
            $unit->label = $unit_arr['label'];
            $unit->save();

            $unit_ids[] = $unit->id;
        }

        $this->company->units()->whereNotIn('id', $unit_ids)->delete();

    	return response()->json($this->company->units, 200);
    }
}