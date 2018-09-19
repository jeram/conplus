<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanyEquipment;
use App\Http\Requests\Api\CompanyEquipment\PostRequest;
use App\Http\Requests\Api\CompanyEquipment\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyEquipmentController extends AuthController
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id)
    {
        $export_type = $request->get('export_type', 'json');

        $equipments = CompanyEquipment::query();

        $equipments->with('status');

        // set up search parameters.
        if ($request->filled('notes')) {
            $equipments->where('notes', 'LIKE', '%' . $request->get('notes') . '%');
        }
        if ($request->filled('name')) {
            $equipments->where('name', 'LIKE', '%' . $request->get('notes') . '%');
        }

        // search
        if ($request->filled('q')) {
            $equipments->where('notes', 'LIKE', '%' . $request->get('q') . '%');
            $equipments->orWhere('name', 'LIKE', '%' . $request->get('q') . '%');
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $equipments);
                break;
            }
            case 'json': {
                return response()->json($equipments->get());
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
    public function store(PostRequest $request, $company_id)
    {        
        $equipment = new CompanyEquipment;
        $equipment->company_id                    = $company_id;
        $equipment->company_equipment_status_id   = $request->get('company_equipment_status_id');
        $equipment->name                          = $request->get('name');
        $equipment->notes                         = $request->get('notes');
        
        $equipment->save();

        return response()->json($equipment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyEquipmentDeposit  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $equipment_id, CompanyEquipment $equipment)
    {
        return response()->json($equipment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\CompanyEquipment $request
     * @param  \App\Models\CompanyEquipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $equipment_id)
    {
        $equipment = CompanyEquipment::find($equipment_id);
        
        if ($request->filled('notes')) {
            $equipment->notes                         = $request->get('notes');
        }
        if ($request->filled('name')) {
            $equipment->name                          = $request->get('name');
        }
        if ($request->filled('company_equipment_status_id')) {
            $equipment->company_equipment_status_id   = $request->get('company_equipment_status_id');
        }
        
        $equipment->save();

        return response()->json($equipment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyEquipmentDeposit  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $equipment_id)
    {
        CompanyEquipment::find($equipment_id)->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->orderBy('name', 'asc')->paginate(10);

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
            'total_amount' => $data->sum('amount')
        ];
        
        return response()->json($response);
    }
}