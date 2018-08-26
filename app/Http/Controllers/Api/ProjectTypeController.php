<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ProjectType;

class ProjectTypeController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $project_types = ProjectType::query();
        
        $project_types->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $project_types->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($project_types->get());
	}

    public function store(Request $request, $company_id) {
        $project_type_ids = array();
        foreach ($request->get('project_types') as $key => $project_type_arr)
        {
            if (isset($project_type_arr['id'])) {
                $project_type = ProjectType::find($project_type_arr['id']);
            } else {
                $project_type = new ProjectType;
                $project_type->company_id = $company_id;
            }            
            $project_type->label = $project_type_arr['label'];
            $project_type->save();

            $project_type_ids[] = $project_type->id;
        }

        $this->company->project_types()->whereNotIn('id', $project_type_ids)->delete();

    	return response()->json($this->company->project_types, 200);
    }
}