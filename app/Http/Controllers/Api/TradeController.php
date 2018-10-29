<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Trade\PostRequest;
use App\Http\Requests\Api\Trade\PatchRequest;
use App\Http\Controllers\Controller;
use App\Models\Trade;
use Carbon\Carbon;

class TradeController extends AuthController
{
	public function __construct() {
        parent::__construct();
        //$this->authorizeResource(Trade::class);
    }

    public function index(Request $request, $company_id, $client_id) {
        $export_type = $request->get('export_type', 'json');

        $trades = Trade::query();
        
        $trades->where('client_id', $client_id);

        // set up search parameters.
        if ($request->filled('description')) {
            $trades->where('description', 'LIKE', '%' . $request->get('description') . '%');
        }

        // search
        if ($request->filled('q')) {
            $trades->where('description', 'LIKE', '%' . $request->get('q') . '%');
        }

        if ($request->filled('date_from')) {
            $trades->whereDate('payment_date', '>=', Carbon::createFromFormat('M j, Y', $request->get('date_from')));
        }

        if ($request->filled('date_to')) {
            $trades->whereDate('payment_date', '<', Carbon::createFromFormat('M j, Y', $request->get('date_to')));
        }

        $trades->select('*', \DB::raw('DATE_FORMAT(payment_date, "%b %e, %Y") as payment_date'));

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $trades);
                break;
            }
            case 'json': {
                return response()->json($trades->get());
                break;
            }
            default: {
                abort(403, 'Unauthorized action. Export type not defined: ' .$export_type);
                break;
            }
        }
	}

    public function show($company_id, $client_id, $trade_id) {
    	return response()->json(Trade::find($trade_id));
    }

    public function store(PostRequest $request, $company_id, $client_id) {
    	$trade = new Trade;
    	$trade->client_id = $client_id;
    	$trade->description = $request->get('description');
    	$trade->ordered = $request->get('ordered');
    	$trade->delivered = $request->get('delivered');
    	$trade->capital = $request->get('capital');
    	$trade->paid_amount = $request->get('paid_amount');
    	$trade->payment_date = $request->get('payment_date');
    	$trade->trade_status_id = $request->get('trade_status_id');
    	$trade->attachment_filename = $request->get('attachment_filename');
    	$trade->save();

    	return response()->json($trade, 200);
    }

    public function update(PatchRequest $request, $company_id, $client_id, $trade_id) {
        $trade = Trade::find($trade_id);

    	if ($request->filled('description')) {
            $trade->description = $request->get('description');
        }

        if ($request->filled('ordered')) {
            $trade->ordered = $request->get('ordered');
        }

        if ($request->filled('delivered')) {
            $trade->delivered = $request->get('delivered');
        }

        if ($request->filled('capital')) {
            $trade->capital = $request->get('capital');
        }

        if ($request->filled('paid_amount')) {
            $trade->paid_amount = $request->get('paid_amount');
        }

        if ($request->filled('payment_date')) {
            $trade->payment_date = $request->get('payment_date');
        }

        if ($request->filled('trade_status_id')) {
            $trade->trade_status_id = $request->get('trade_status_id');
        }

        if ($request->filled('payment_date')) {
            $trade->payment_date = $request->get('payment_date');
        }

        if ($request->filled('attachment_filename')) {
            $trade->attachment_filename = $request->get('attachment_filename');
        }

    	$trade->save();

    	return response()->json($trade);
    }

    public function destroy($company_id, $client_id, $trade_id) {
    	Trade::find($trade_id)->delete();

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
            'total_capital_amount' => $data->sum('capital'),
            'total_paid_amount' => $data->sum('paid_amount')
        ];
        
        return response()->json($response);
    }
}