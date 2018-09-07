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
        $export_type = $request->get('export_type', 'json');

        $projects = Project::query();
        
        $projects->where('company_id', $this->company->id);

        $projects->with('details');

        // set up search parameters.
        if ($request->filled('name')) {
            $projects->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        // search
        if ($request->filled('q')) {
            $projects->where('name', 'LIKE', '%' . $request->get('q') . '%');
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $projects);
                break;
            }
            case 'json': {
                return response()->json($projects->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
	}

    public function show($company_id, $project_id) {
    	return response()->json(Project::find($project_id)->load('details'));
    }

    public function store(PostRequest $request, $company_id) {
    	$project = new Project;
    	$project->name = $request->get('name');
    	$project->cost = $request->get('cost');
        $project->company_id = $company_id;
    	$project->is_active = $request->get('is_active');
    	$project->save();

    	return response()->json($project, 200);
    }

    public function update(PatchRequest $request, $company_id, $project_id) {
        $project = Project::find($project_id);

    	if ($request->filled('name')) {
            $project->name = $request->get('name');
        }

        if ($request->filled('cost')) {
            $project->cost = $request->get('cost');
        }

        if ($request->filled('is_active')) {
            $project->is_active = $request->get('is_active');
        }

    	$project->save();

    	return response()->json($project);
    }

    public function destroy($company_id, $project_id) {
    	Project::find($project_id)->delete();

        return response()->json([], 202);
    }

    public function restore($company_id, $project_id) {
    	$project = Project::withTrashed()->findOrFail($project_id);
        $project->restore();

        return response()->json($project);
    }

    public function toggleActive($company_id, $project_id) {
        $project = Project::find($project_id);
        $project->is_active = $project->is_active ? false : true;
        $project->save();
        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'data' => $data,
        ];
        
        return response()->json($response);
    }
}