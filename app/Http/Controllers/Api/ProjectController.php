<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Project\PostRequest;
use App\Http\Requests\Api\Project\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Project::class);
    }

    public function index(Request $request, $company_id) {
		$this->authorize('index', Project::class);

        $projects = new Project;
        
        $projects->where('company_id', $this->company->id);

        $projects->with('details');

        // set up search parameters.
        if ($request->filled('name')) {
            $projects->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        return response()->json($projects);
	}

    public function show($company_id, Project $project) {
    	return response()->json($project->load('details'));
    }

    public function store(PostRequest $request, $company_id) {
    	$project = new Project;
    	$project->name = $request->get('name');
    	$project->cost = $request->get('cost');
    	$project->company_id = $company_id;
    	$project->save();

    	return response()->json($project, 200);
    }

    public function update(PatchRequest $request, $company_id, Project $project) {
    	if ($request->filled('name')) {
            $project->name = $request->get('name');
        }

        if ($request->filled('cost')) {
            $project->cost = $request->get('cost');
        }

    	$project->save();

    	return response()->json($project);
    }

    public function destroy($company_id, Project $project) {
    	$project->delete();

        return response()->json([], 202);
    }

    public function restore($company_id, $project_id) {
    	$project = Campaign::withTrashed()->findOrFail($project_id);
        $this->authorize('restore', $project);
        $project->restore();

        return response()->json($project);
    }

    public function toggleActive($company_id, Project $project) {

    }
}