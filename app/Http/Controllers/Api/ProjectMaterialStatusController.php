<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ProjectMaterialStatus;

class ProjectMaterialStatusController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $project_material_statuses = ProjectMaterialStatus::query();
        
        $project_material_statuses->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $project_material_statuses->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($project_material_statuses->get());
	}

    public function store(Request $request, $company_id) {
        $project_material_status_ids = array();
        foreach ($request->get('project_material_statuses') as $key => $project_material_status_arr)
        {
            if (isset($project_material_status_arr['id'])) {
                $project_material_status = ProjectMaterialStatus::find($project_material_status_arr['id']);
            } else {
                $project_material_status = new ProjectMaterialStatus;
                $project_material_status->company_id = $company_id;
            }            
            $project_material_status->label = $project_material_status_arr['label'];
            $project_material_status->save();

            $project_material_status_ids[] = $project_material_status->id;
        }

        $this->company->project_material_statuses()->whereNotIn('id', $project_material_status_ids)->delete();

    	return response()->json($this->company->project_material_statuses, 200);
    }
}