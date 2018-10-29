<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Project;
use App\Models\Trade;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{
	//public function __construct() {
        //parent::__construct();
        //$this->authorizeResource(Company::class);
    //}

    public function index($report_method) {        
        return $this->{$report_method}();        
	}

    public function get_total_project_deposits() {
        $company_id = Input::get('company_id');
        $project_id = Input::get('project_id');

        $project = Project::find($project_id);
        $total = (float) $project->deposits()->whereDate('payment_date', '<', Carbon::now())->sum('amount');
        //var_dump($total);
        //return response()->json(compact('total'));
        return response()->json($total);
    }

    public function get_total_project_payments() {
        $company_id = Input::get('company_id');
        $project_id = Input::get('project_id');

        $project = Project::find($project_id);
        $total = $project->payments()->whereDate('payment_date', '<', Carbon::now())->sum('amount');
        return response()->json($total);
    }

    public function get_phase_progress() {
        $company_id = Input::get('company_id');
        $project_id = Input::get('project_id');

        $project = Project::find($project_id);
        $phases = [];
        
        
        foreach ($project->phases as $phase) {

            $total = (int) $phase->materials()->sum('quantity');
            $actual = (int) $phase->materials()->sum('used_qty');
            $percentage = ($total && $actual) > 0 ? round(($actual/$total) * 100) : 0;

            array_push($phases,[
                    'phase' => $phase,
                    'total' =>  $total,
                    'used' => $actual,
                    'percentage' => $percentage
                ]
            );
        }
        return response()->json($phases);
    }

    public function get_upcoming_payments() {
        $company_id = Input::get('company_id');

        $company = Company::find($company_id);
        $upcoming_payments = $company->payments()
                            ->with('project')
                            ->whereDate('payment_date', '>=', Carbon::now())
                            ->whereDate('payment_date', '<', Carbon::now()->addDays(30))                            
                            ->limit(10)
                            ->orderBy('payment_date', 'asc')
                            ->get();

        return response()->json($upcoming_payments);
    }

    public function get_total_deposits_vs_payments() {
        $company_id = Input::get('company_id');
        $project_id = Input::get('project_id');

        $project = Project::find($project_id);
        return response()->json([
            'deposits' => $project->deposits()->whereDate('payment_date', '<', Carbon::now())->sum('amount'),
            'payments' => $project->payments()->whereDate('payment_date', '<', Carbon::now())->sum('amount'),
        ]);
    }

    public function get_total_capital_vs_paid() {
        $company_id = Input::get('company_id');

        $trade = Trade::query();
        return response()->json([
            'total_capital' => $trade->sum('capital'),
            'total_paid' => $trade->sum('paid'),
        ]);
    }

    public function get_payments_data() {
        $company_id = Input::get('company_id');
        $project_id = Input::get('project_id');
        $date_from = Input::get('date_from');
        $date_to = Input::get('date_to');

        $project = Project::find($project_id);

        $from = \DateTime::createFromFormat('M j, Y', $date_from);
        $to = \DateTime::createFromFormat('M j, Y', $date_to);

        $payments = $project->payments()
                        ->where('payment_date', '>=', $from->format('Y-m-d') . '00:00:00')
                        ->where('payment_date', '<=', $to->format('Y-m-d') . '00:00:00')
                        ->get();

        return response()->json($payments);
    }
}