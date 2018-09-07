<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectDeposit;
use App\Models\Project;
use App\Http\Requests\Api\ProjectDeposit\PostRequest;
use App\Http\Requests\Api\ProjectDeposit\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectDepositController extends AuthController
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

        $deposits = Project::find($project_id)->deposits();

        // set up search parameters.
        if ($request->filled('notes')) {
            $deposits->where('notes', 'LIKE', '%' . $request->get('notes') . '%');
        }

        // search
        if ($request->filled('q')) {
            $deposits->where('notes', 'LIKE', '%' . $request->get('q') . '%');
        }

        $deposits->select('*', \DB::raw('DATE_FORMAT(payment_date, "%b %e, %Y") as payment_date'));
        $deposits->with('type');

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $deposits);
                break;
            }
            case 'json': {
                return response()->json($deposits->get());
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
        $deposit = new ProjectDeposit;
        $deposit->company_id               = $company_id;
        $deposit->project_id               = $project_id;
        $deposit->amount                   = $request->get('amount');
        $deposit->company_deposit_type_id  = $request->get('company_deposit_type_id');
        $deposit->payment_date             = $request->get('payment_date');
        $deposit->notes                    = $request->get('notes');
        
        $deposit->save();

        return response()->json($deposit, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $project_id, ProjectDeposit $deposit)
    {
        return response()->json($deposit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\ProjectDeposit $request
     * @param  \App\Models\ProjectDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $project_id, $deposit_id)
    {
        $deposit = ProjectDeposit::find($deposit_id);
        
        if ($request->filled('notes')) {
            $deposit->notes                     = $request->get('notes');
        }
        if ($request->filled('amount')) {
            $deposit->amount                    = $request->get('amount');
        }
        if ($request->filled('payment_date')) {
            $deposit->payment_date              = $request->get('payment_date');
        }
        if ($request->filled('company_deposit_type_id')) {
            $deposit->company_deposit_type_id   = $request->get('company_deposit_type_id');
        }

        $deposit->save();

        return response()->json($deposit, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $project_id, $deposit_id)
    {
        ProjectDeposit::find($deposit_id)->delete();

        return response()->json([], 202);
    }

    private function processDataTableExport($request, $query) {
        $data = $query->orderBy('payment_date', 'desc')->paginate(10);

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