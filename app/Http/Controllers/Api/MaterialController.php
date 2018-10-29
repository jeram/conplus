<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Material;

class MaterialController extends AuthController
{
	public function __construct() {
        parent::__construct();
    }

    public function index(Request $request, $company_id) {

        $export_type = $request->get('export_type', 'json');

        $materials = Material::query();
        
        //$materials->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('name')) {
            $materials->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        // search
        if ($request->filled('q')) {
            $materials->where('name', 'LIKE', '%' . $request->get('q') . '%');

            $materials->orWhereHas('unit', function( $query ) use ( $request ){
                $query->where('label', 'LIKE', '%' . $request->get('q') . '%');
            });
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $materials);
                break;
            }
            case 'select2-option': {
                return $this->processSelect2OptionExport($request, $materials);
                break;
            }
            case 'autocomplete': {
                return $this->processAutocompleteExport($request, $materials);
                break;
            }
            case 'json': {
                return response()->json($materials->with('unit')->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
        
	}

    public function show($company_id, Material $material) {
        return response()->json($material);
    }

    public function store(Request $request, $company_id) {        
        $material = new Material;
        $material->company_id = $company_id;
        $material->material_category_id = 1;
        $material->unit_of_measurement_id = 1;
        $material->name = $request->get('name');
        $material->save();

    	return response()->json($material, 200);
    }

    public function update(Request $request, $company_id, Material $material) {

        if ($material->filled('name')) {
            $material->name = $request->get('name');
        }

        $material->save();

        return response()->json($material);
    }

    public function destroy($company_id, Material $material) {
        $material->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->with('unit')->orderBy('name')->paginate(10);

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

    private function processSelect2OptionExport($request, $query) {
        $query->with('unit')->orderBy('name')->paginate(10);

        $options = [];
        foreach ($query->get() as $material) {
            array_push($options,[
                    'value' => $material->id,
                    'label' => $material->name,
                ]);
        }
        return response()->json($options);
    }

    
    private function processAutocompleteExport($request, $query) {
        $query->with('unit')->orderBy('name')->paginate(10);

        $options = [];
        foreach ($query->get() as $material) {
            array_push($options,[
                    'id' => $material->id,
                    'value' => $material->id,
                    'label' => $material->name,
                ]);
        }
        return response()->json($options);
    }


}