<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectMaterial;
use App\Models\Project;
use App\Http\Requests\Api\ProjectMaterial\PostRequest;
use App\Http\Requests\Api\ProjectMaterial\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectMaterialController extends AuthController
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
        $export_type = $request->get('export_type', 'json');

        $materials = Project::find($project_id)->materials();

        // set up search parameters.
        if ($request->filled('label')) {
            $materials->where('label', 'LIKE', '%' . $request->get('label') . '%');
        }
        if ($request->filled('project_phase_id')) {
            $materials->where('project_phase_id', $request->get('project_phase_id'));
        }

        // search
        if ($request->filled('q')) {
            $materials->where('label', 'LIKE', '%' . $request->get('q') . '%');
            $materials->orWhere('quantity', 'LIKE', '%' . $request->get('q') . '%');
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $materials);
                break;
            }
            case 'json': {
                return response()->json($materials->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, $company_id, $project_id)
    {        
        $material = new ProjectMaterial;
        $material->company_id           = $company_id;
        $material->project_id           = $project_id;
        $material->project_phase_id     = $request->get('project_phase_id');
        $material->quantity             = $request->get('quantity');
        $material->price                = $request->get('price');
        $material->total_price          = $request->get('total_price');
        $material->to_order_qty         = $request->get('to_order_qty');
        $material->warehouse_qty        = $request->get('warehouse_qty');
        $material->on_site_unused_qty   = $request->get('on_site_unused_qty');
        $material->used_qty             = $request->get('used_qty');
        $material->warehouse_qty        = $request->get('warehouse_qty');
        $material->notes                = $request->get('notes');
        $material->label                = $request->get('label');
        $material->material_id          = $request->get('material_id');
        
        $material->save();

        return response()->json($material, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectMaterial  $material
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $project_id, ProjectMaterial $material)
    {
        return response()->json($material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\ProjectMaterial $request
     * @param  \App\Models\ProjectMaterial  $material
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $project_id, $material_id)
    {
        $material = ProjectMaterial::find($material_id);
        
        if ($request->filled('project_phase_id')) {
            $material->project_phase_id     = $request->get('project_phase_id');
        }
        if ($request->filled('quantity')) {
            $material->quantity             = $request->get('quantity');
        }
        if ($request->filled('price')) {
            $material->price                = $request->get('price');
        }
        if ($request->filled('total_price')) {
            $material->total_price          = $request->get('total_price');
        }
        if ($request->filled('to_order_qty')) {
            $material->to_order_qty         = $request->get('to_order_qty');
        }
        if ($request->filled('warehouse_qty')) {
            $material->warehouse_qty        = $request->get('warehouse_qty');
        }
        if ($request->filled('on_site_unused_qty')) {
            $material->on_site_unused_qty   = $request->get('on_site_unused_qty');
        }
        if ($request->filled('used_qty')) {
            $material->used_qty             = $request->get('used_qty');
        }
        if ($request->filled('notes')) {
            $material->notes                = $request->get('notes');
        }
        if ($request->filled('label')) {
            $material->label                = $request->get('label');
        }
        if ($request->filled('material_id')) {
            $material->material_id          = $request->get('material_id');
        }
        
        $material->save();

        return response()->json($material, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectMaterial  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $project_id, $material_id)
    {
        ProjectMaterial::find($material_id)->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->orderBy('label')->paginate(10);

        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'data' => $data
        ];
        
        return response()->json($response);
    }
}