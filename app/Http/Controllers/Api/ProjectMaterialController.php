<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectMaterial;
use App\Models\Project;
use App\Models\Material;
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
        $project_material = new ProjectMaterial;

        $project_material->company_id           = $company_id;
        $project_material->project_id           = $project_id;
        $project_material->project_phase_id     = $request->get('project_phase_id');
        $project_material->quantity             = $request->get('quantity');
        $project_material->price                = $request->get('price');
        $project_material->total_price          = $request->get('total_price');
        $project_material->to_order_qty         = $request->get('to_order_qty');
        $project_material->warehouse_qty        = $request->get('warehouse_qty');
        $project_material->on_site_unused_qty   = $request->get('on_site_unused_qty');
        $project_material->used_qty             = $request->get('used_qty');
        $project_material->warehouse_qty        = $request->get('warehouse_qty');
        $project_material->notes                = $request->get('notes');
        $project_material->label                = $request->get('label');
        $project_material->material_id          = $request->get('material_id');

        // check if material defined is not in materials table
        if(!$request->get('material_id')) {
            // add it to material's table
            $material = new Material;
            $material->company_id               = $company_id;
            $material->material_category_id     = 1;
            $material->unit_of_measurement_id   = 1;
            $material->name                     = $request->get('label');
            $material->save();

            // set project material values
            $project_material->material_id      = $material->id;
        }
        
        $project_material->save();

        return response()->json($project_material, 200);
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
        $project_material = ProjectMaterial::find($material_id);
        
        if ($request->filled('project_phase_id')) {
            $project_material->project_phase_id     = $request->get('project_phase_id');
        }
        if ($request->filled('quantity')) {
            $project_material->quantity             = $request->get('quantity');
        }
        if ($request->filled('price')) {
            $project_material->price                = $request->get('price');
        }
        if ($request->filled('total_price')) {
            $project_material->total_price          = $request->get('total_price');
        }
        if ($request->filled('to_order_qty')) {
            $project_material->to_order_qty         = $request->get('to_order_qty');
        }
        if ($request->filled('warehouse_qty')) {
            $project_material->warehouse_qty        = $request->get('warehouse_qty');
        }
        if ($request->filled('on_site_unused_qty')) {
            $project_material->on_site_unused_qty   = $request->get('on_site_unused_qty');
        }
        if ($request->filled('used_qty')) {
            $project_material->used_qty             = $request->get('used_qty');
        }
        if ($request->filled('notes')) {
            $project_material->notes                = $request->get('notes');
        }
        if ($request->filled('label')) {
            $project_material->label                = $request->get('label');
        }
        if ($request->filled('material_id')) {
            $project_material->material_id          = $request->get('material_id');
        }
        
        // check if material defined is not in materials table
        if(!$request->get('material_id')) {
            // add it to material's table
            $material = new Material;
            $material->company_id               = $company_id;
            $material->material_category_id     = 1;
            $material->unit_of_measurement_id   = 1;
            $material->name                     = $request->get('label');
            $material->save();

            // set project material values
            $project_material->material_id      = $material->id;
        }

        $project_material->save();

        return response()->json($project_material, 200);
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