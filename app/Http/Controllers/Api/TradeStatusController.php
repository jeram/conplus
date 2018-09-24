<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TradeStatus\PostRequest;
use App\Http\Requests\Api\TradeStatus\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TradeStatus;

class TradeStatusController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Project::class);
    }

    public function index(Request $request, $company_id) {
		//$this->authorize('index', TradeStatus::class);

        $trade_statuses = TradeStatus::query();
        
        $trade_statuses->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('label')) {
            $trade_statuses->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($trade_statuses->get());
	}

    /*public function show($company_id, TradeStatus $trade_status) {
        $project
    	return response()->json($project->load('details'));
    }*/

    public function store(Request $request, $company_id) {
        $trade_status_ids = array();
        foreach ($request->get('trade_statuses') as $key => $trade_status_arr)
        {
            if (isset($trade_status_arr['id'])) {
                $trade_status = TradeStatus::find($trade_status_arr['id']);
            } else {
                $trade_status = new TradeStatus;
                $trade_status->company_id = $company_id;
            }            
            $trade_status->label = $trade_status_arr['label'];
            $trade_status->save();

            $trade_status_ids[] = $trade_status->id;
        }

        $this->company->trade_statuses()->whereNotIn('id', $trade_status_ids)->delete();

    	return response()->json($this->company->trade_statuses, 200);
    }

    public function update(PatchRequest $request, $company_id) {
    	/*$project = Project::find($project_id);
        $project->statuses()->sync($request->get('trade_statuses'));

        return response()->json($project->statuses, 200);*/
    }

    public function destroy($company_id, TradeStatus $trade_status) {
    	$trade_status->delete();

        return response()->json([], 202);
    }
}