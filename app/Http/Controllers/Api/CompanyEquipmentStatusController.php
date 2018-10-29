<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\CompanyEquipmentStatus;

class CompanyEquipmentStatusController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $statuses = CompanyEquipmentStatus::query();
        
        $statuses->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('name')) {
            $statuses->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        return response()->json($statuses->get());
	}

    public function store(Request $request, $company_id) {
        $status_ids = array();
        foreach ($request->get('company_equipment_statuses') as $key => $status_arr)
        {
            if (isset($status_arr['id'])) {
                $status = CompanyEquipmentStatus::find($status_arr['id']);
            } else {
                $status = new CompanyEquipmentStatus;
                $status->company_id = $company_id;
            }            
            $status->label = $status_arr['label'];
            $status->save();

            $status_ids[] = $status->id;
        }

        $this->company->equipment_statuses()->whereNotIn('id', $status_ids)->whereNotIn('label', ['Operational', 'Non Operational'])->delete();

    	return response()->json($this->company->equipment_statuses, 200);
    }
}