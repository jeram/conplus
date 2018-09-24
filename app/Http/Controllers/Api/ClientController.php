<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Client\PostRequest;
use App\Http\Requests\Api\Client\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Client::class);
    }

    public function index(Request $request, $company_id) {
        $export_type = $request->get('export_type', 'json');

        $clients = Client::query();
        
        $clients->where('company_id', $this->company->id);

        // set up search parameters.
        if ($request->filled('name')) {
            $clients->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }
        if ($request->filled('contact_person')) {
            $clients->where('contact_person', 'LIKE', '%' . $request->get('contact_person') . '%');
        }

        // search
        if ($request->filled('q')) {
            $clients->where('name', 'LIKE', '%' . $request->get('q') . '%');
            $clients->orWhere('contact_person', 'LIKE', '%' . $request->get('q') . '%');
            $clients->orWhere('email', 'LIKE', '%' . $request->get('q') . '%');
        }

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $clients);
                break;
            }
            case 'json': {
                return response()->json($clients->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
	}

    public function show($company_id, $client_id) {
    	return response()->json(Client::find($client_id)->load('details'));
    }

    public function store(PostRequest $request, $company_id) {
    	$client = new Client;
    	$client->name = $request->get('name');
    	$client->contact_person = $request->get('contact_person');
        $client->company_id = $company_id;
        $client->contact_number = $request->get('contact_number');
    	$client->email = $request->get('email');
    	$client->notes = $request->get('notes');
    	$client->save();

    	return response()->json($client, 200);
    }

    public function update(PatchRequest $request, $company_id, $client_id) {
        $client = Client::find($client_id);

    	if ($request->filled('name')) {
            $client->name = $request->get('name');
        }

        if ($request->filled('contact_person')) {
            $client->contact_person = $request->get('contact_person');
        }

        if ($request->filled('contact_number')) {
            $client->contact_number = $request->get('contact_number');
        }
        
        if ($request->filled('contact_number')) {
            $client->contact_number = $request->get('contact_number');
        }
        if ($request->filled('email')) {
            $client->email = $request->get('email');
        }
        if ($request->filled('notes')) {
            $client->notes = $request->get('notes');
        }

    	$client->save();

    	return response()->json($client);
    }

    public function destroy($company_id, $client_id) {
    	Client::find($client_id)->delete();

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
        ];
        
        return response()->json($response);
    }
}