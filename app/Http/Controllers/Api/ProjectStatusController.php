<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\ProjectStatus\PostRequest;
use App\Http\Requests\Api\ProjectStatus\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStatus;

class ProjectStatusController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Project::class);
    }

    public function index(Request $request, $company_id) {
		//$this->authorize('index', ProjectStatus::class);

        $project_statuses = ProjectStatus::query();
        
        $project_statuses->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $project_statuses->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($project_statuses->get());
	}

    /*public function show($company_id, ProjectStatus $project_status) {
        $project
    	return response()->json($project->load('details'));
    }*/

    public function store(Request $request, $company_id) {
        $project_status_ids = array();
        foreach ($request->get('project_statuses') as $key => $project_status_arr)
        {
            if (isset($project_status_arr['id'])) {
                $project_status = ProjectStatus::find($project_status_arr['id']);
            } else {
                $project_status = new ProjectStatus;
                $project_status->company_id = $company_id;
            }            
            $project_status->label = $project_status_arr['label'];
            $project_status->save();

            $project_status_ids[] = $project_status->id;
        }

        $this->company->project_statuses()->whereNotIn('id', $project_status_ids)->whereNotIn('label', ['Completed'])->delete();

    	return response()->json($this->company->project_statuses, 200);
    }

    public function update(PatchRequest $request, $company_id) {
    	/*$project = Project::find($project_id);
        $project->statuses()->sync($request->get('project_statuses'));

        return response()->json($project->statuses, 200);*/
    }

    public function destroy($company_id, ProjectStatus $project_status) {
    	$project_status->delete();

        return response()->json([], 202);
    }
}