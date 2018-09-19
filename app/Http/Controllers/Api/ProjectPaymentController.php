<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectPayment;
use App\Models\Project;
use App\Http\Requests\Api\ProjectPayment\PostRequest;
use App\Http\Requests\Api\ProjectPayment\PatchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectPaymentController extends AuthController
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

        $payments = Project::find($project_id)->payments();

        // set up search parameters.
        if ($request->filled('notes')) {
            $payments->where('notes', 'LIKE', '%' . $request->get('notes') . '%');
        }
        if ($request->filled('check_number')) {
            $payments->where('check_number', $request->get('check_number'));
        }

        // search
        if ($request->filled('q')) {
            $payments->where('notes', 'LIKE', '%' . $request->get('q') . '%');
            $payments->orWhere('check_number', 'LIKE', '%' . $request->get('q') . '%');
        }

        $payments->select('*', \DB::raw('DATE_FORMAT(payment_date, "%b %e, %Y") as payment_date'));
        $payments->with('type');

        switch($export_type) {
            case 'data-table': {
                return $this->processDataTableExport($request, $payments);
                break;
            }
            case 'json': {
                return response()->json($payments->get());
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
        $payment = new ProjectPayment;
        $payment->company_id               = $company_id;
        $payment->project_id               = $project_id;
        $payment->phase_id                 = $request->get('phase_id');
        $payment->amount                   = $request->get('amount');
        $payment->company_payment_type_id  = $request->get('company_payment_type_id');
        $payment->check_number             = $request->get('check_number');
        $payment->payment_date             = $request->get('payment_date');
        $payment->notes                    = $request->get('notes');
        $payment->attachment_filename      = $request->get('attachment_filename');
        
        $payment->save();

        return response()->json($payment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectPayment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $project_id, ProjectPayment $payment)
    {
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\ProjectPayment $request
     * @param  \App\Models\ProjectPayment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PatchRequest $request, $company_id, $project_id, $payment_id)
    {
        $payment = ProjectPayment::find($payment_id);
        
        if ($request->filled('phase_id')) {
            $payment->phase_id                  = $request->get('phase_id');
        }
        if ($request->filled('notes')) {
            $payment->notes                     = $request->get('notes');
        }
        if ($request->filled('amount')) {
            $payment->amount                    = $request->get('amount');
        }
        if ($request->filled('check_number')) {
            $payment->check_number              = $request->get('check_number');
        }
        if ($request->filled('payment_date')) {
            $payment->payment_date              = $request->get('payment_date');
        }
        if ($request->filled('company_payment_type_id')) {
            $payment->company_payment_type_id   = $request->get('company_payment_type_id');
        }
        
        $payment->attachment_filename       = $request->get('attachment_filename');
        

        $payment->save();

        return response()->json($payment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectPayment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $project_id, $payment_id)
    {
        ProjectPayment::find($payment_id)->delete();

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