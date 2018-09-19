<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanyEquipmentHistory;
use App\Http\Requests\Api\CompanyEquipmentHistory\PostRequest;
use App\Http\Requests\Api\CompanyEquipmentHistory\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyEquipmentHistoryController extends AuthController
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id, $equipment_id)
    {
        $export_type = $request->get('export_type', 'json');

        $equipments = CompanyEquipmentHistory::query();

        $equipments->where('company_id', $this->company->id);
        $equipments->where('equipment_id', $equipment_id);

        // set up search parameters.
        if ($request->filled('description')) {
            $equipments->where('description', 'LIKE', '%' . $request->get('notes') . '%');
        }

        // search
        if ($request->filled('q')) {
            $equipments->where('description', 'LIKE', '%' . $request->get('q') . '%');
        }

        $equipments->select('*', \DB::raw('DATE_FORMAT(date, "%b %e, %Y") as date'));

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
    public function store(PostRequest $request, $company_id, $equipment_id)
    {        
        $equipment = new CompanyEquipmentHistory;
        $equipment->company_id      = $company_id;
        $equipment->equipment_id    = $equipment_id;
        $equipment->description     = $request->get('description');
        $equipment->date            = $request->get('date');
        
        $equipment->save();

        return response()->json($equipment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyEquipmentHistory  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $equipment_id, CompanyEquipmentHistory $equipment)
    {
        return response()->json($equipment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\CompanyEquipmentHistory $request
     * @param  \App\Models\CompanyEquipmentHistory  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $equipment_id)
    {
        $equipment = CompanyEquipmentHistory::find($equipment_id);
        
        if ($request->filled('description')) {
            $equipment->description = $request->get('description');
        }
        if ($request->filled('date')) {
            $equipment->date = $request->get('date');
        }
        $equipment->save();

        return response()->json($equipment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyEquipmentHistory  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $equipment_id)
    {
        CompanyEquipmentHistory::find($equipment_id)->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->orderBy('date', 'desc')->paginate(10);

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