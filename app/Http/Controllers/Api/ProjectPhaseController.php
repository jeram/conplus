<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectPhase;
use App\Models\Project;
use App\Http\Requests\Api\ProjectPhase\PostRequest;
use App\Http\Requests\Api\ProjectPhase\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectPhaseController extends AuthController
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id, $project_id)
    {
        $phases = Project::find($project_id)->phases();

        // set up search parameters.
        if ($request->filled('label')) {
            $phases->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }

        return response()->json($phases->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, $company_id, $project_id)
    {
        $phase = new ProjectPhase;
        $phase->company_id = $company_id;
        $phase->project_id = $project_id;
        $phase->label = $request->get('label');
        $phase->total_cost_estimation = $request->get('total_cost_estimation');
        $phase->project_percentage = $request->get('project_percentage');

        if ($request->filled('start_date')) {
            $phase->start_date = $request->get('start_date');
        }

        if ($request->filled('finish_date')) {
            $phase->finish_date = $request->get('finish_date');
        }

        $phase->notes = $request->get('notes');
        
        $phase->save();

        return response()->json($phase, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectPhase  $phase
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $project_id, ProjectPhase $phase)
    {
        return response()->json($phase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\ProjectPhase $request
     * @param  \App\Models\ProjectPhase  $phase
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, ProjectPhase $phase)
    {
        $phase->label = $request->get('label');
        $phase->total_cost_estimation = $request->get('total_cost_estimation');

        if ($request->filled('start_date')) {
            $phase->start_date = $request->get('start_date');
        }
        
        if ($request->filled('finish_date')) {
            $phase->finish_date = $request->get('finish_date');
        }

        $phase->notes = $request->get('notes');
        
        $phase->save();

        return response()->json($phase, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectPhase  $phase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectPhase $phase)
    {
        $phase->delete();

        return response()->json([], 202);
    }
}