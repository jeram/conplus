<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ProjectNoteStatus;

class ProjectNoteStatusController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $project_note_status = ProjectNoteStatus::query();
        
        $project_note_status->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $project_note_status->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($project_note_status->get());
	}

    public function store(Request $request, $company_id) {
        $project_note_status_ids = array();
        foreach ($request->get('project_note_statuses') as $key => $project_note_status_arr)
        {
            if (isset($project_note_status_arr['id'])) {
                $project_note_status = ProjectNoteStatus::find($project_note_status_arr['id']);
            } else {
                $project_note_status = new ProjectNoteStatus;
                $project_note_status->company_id = $company_id;
            }            
            $project_note_status->label = $project_note_status_arr['label'];
            $project_note_status->save();

            $project_note_status_ids[] = $project_note_status->id;
        }

        $this->company->project_note_statuses()->whereNotIn('id', $project_note_status_ids)->delete();

    	return response()->json($this->company->project_note_statuses, 200);
    }
}