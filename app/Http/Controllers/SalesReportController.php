<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\SalesAchievement;
use App\Models\DailySales;
use App\Models\InHouseSales;
use App\Models\NutritionistSales;
use App\Models\ConsultantSales;
use App\Models\UserMaster;
use App\Models\NewBranch;
use App\Models\IssueDepartment;
use App\Services\MasterDataCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'all');
        $targetType = $request->get('target_type', '');
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');
        $monthYear = $request->get('month_year', date('Y-m'));

        $branches = MasterDataCacheService::getBranches();
        $departments = MasterDataCacheService::getDepartments();
        $employees = UserMaster::orderBy('FullName')->limit(200)->get();

        $reportData = $this->generateReport($reportType, $targetType, $branchId, $employeeId, $monthYear);

        return view('reports.sales-projection', compact('reportData', 'branches', 'departments', 'employees', 'reportType', 'targetType', 'branchId', 'employeeId', 'monthYear'));
    }

    public function indexComplete(Request $request)
    {
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');
        $monthYear = $request->get('month_year', date('Y-m'));

        $branches = NewBranch::where('is_active', 1)->orderBy('branch_name')->get();
        $employees = UserMaster::orderBy('FullName')->limit(200)->get();

        $reportData = $this->generateReport('all', '', $branchId, $employeeId, $monthYear);

        return view('reports.sales-projection-complete', compact('reportData', 'branches', 'employees', 'branchId', 'employeeId', 'monthYear'));
    }

    private function generateReport($reportType, $targetType, $branchId, $employeeId, $monthYear)
    {
        if ($monthYear) {
            [$year, $month] = explode('-', $monthYear);
            $dateFrom = "$year-$month-01";
            $dateTo = date('Y-m-t', strtotime($dateFrom));
        } else {
            $dateFrom = date('Y-m-01');
            $dateTo = date('Y-m-t');
        }

        // Get daily sales data
        $dailySalesQuery = DailySales::query()
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo);
        if ($branchId) $dailySalesQuery->where('branch_id', $branchId);
        if ($employeeId) $dailySalesQuery->where('user_id', $employeeId);
        $dailySales = $dailySalesQuery->get();

        // Get in-house sales data
        $inHouseSalesQuery = InHouseSales::query()
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo);
        if ($branchId) $inHouseSalesQuery->where('branch_id', $branchId);
        if ($employeeId) $inHouseSalesQuery->where('user_id', $employeeId);
        $inHouseSales = $inHouseSalesQuery->get();

        // Get nutritionist sales data
        $nutritionistSalesQuery = NutritionistSales::query()
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo);
        if ($branchId) $nutritionistSalesQuery->where('branch_id', $branchId);
        if ($employeeId) $nutritionistSalesQuery->where('user_id', $employeeId);
        $nutritionistSales = $nutritionistSalesQuery->get();

        // Get consultant sales data
        $consultantSalesQuery = ConsultantSales::query()
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo);
        if ($branchId) $consultantSalesQuery->where('branch_id', $branchId);
        if ($employeeId) $consultantSalesQuery->where('user_id', $employeeId);
        $consultantSales = $consultantSalesQuery->get();

        // Calculate totals
        $totalDailySales = $dailySales->sum('day_sales');
        $totalInHouseSales = $inHouseSales->sum('day_sales');
        $totalNutritionistSales = $nutritionistSales->sum('sales_amount');
        $totalConsultantSales = $consultantSales->sum('sales_amount');
        $totalRevenue = $totalDailySales + $totalInHouseSales + $totalNutritionistSales + $totalConsultantSales;

        $totalVisits = $dailySales->sum('visits');
        $totalJoined = $dailySales->sum('joined');
        $totalCCAppointments = $dailySales->sum('cc_appointments');

        // Branch-wise calculations
        $branches = NewBranch::where('is_active', 1)->get();
        $branchData = [];

        foreach ($branches as $branch) {
            $branchDaily = $dailySales->where('branch_id', $branch->branch_id)->sum('day_sales');
            $branchInHouse = $inHouseSales->where('branch_id', $branch->branch_id)->sum('day_sales');
            $branchNutritionist = $nutritionistSales->where('branch_id', $branch->branch_id)->sum('sales_amount');
            $branchConsultant = $consultantSales->where('branch_id', $branch->branch_id)->sum('sales_amount');
            $branchTotal = $branchDaily + $branchInHouse + $branchNutritionist + $branchConsultant;

            $branchVisits = $dailySales->where('branch_id', $branch->branch_id)->sum('visits');
            $branchJoined = $dailySales->where('branch_id', $branch->branch_id)->sum('joined');

            if ($branchTotal > 0 || $branchVisits > 0) {
                $branchData[] = [
                    'branch_id' => $branch->branch_id,
                    'branch_name' => $branch->branch_name,
                    'daily_sales' => $branchDaily,
                    'in_house_sales' => $branchInHouse,
                    'nutritionist_sales' => $branchNutritionist,
                    'consultant_sales' => $branchConsultant,
                    'total_sales' => $branchTotal,
                    'visits' => $branchVisits,
                    'joined' => $branchJoined,
                ];
            }
        }

        $summary = [
            'period' => $monthYear,
            'daily_sales' => $totalDailySales,
            'in_house_sales' => $totalInHouseSales,
            'nutritionist_sales' => $totalNutritionistSales,
            'consultant_sales' => $totalConsultantSales,
            'total_revenue' => $totalRevenue,
            'total_visits' => $totalVisits,
            'total_joined' => $totalJoined,
            'total_cc_appointments' => $totalCCAppointments,
            'conversion_percent' => $totalVisits > 0 ? round(($totalJoined / $totalVisits) * 100, 2) : 0,
        ];

        return [
            'summary' => $summary,
            'branch_data' => $branchData,
            'daily_sales' => $dailySales,
            'in_house_sales' => $inHouseSales,
            'nutritionist_sales' => $nutritionistSales,
            'consultant_sales' => $consultantSales,
        ];
    }

    public function exportPdf(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $targetType = $request->get('target_type', 'month');
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');
        $monthYear = $request->get('month_year', date('Y-m'));

        $reportData = $this->generateReport($reportType, $targetType, $branchId, $employeeId, $monthYear);

        $pdf = \PDF::loadView('reports.sales-projection-pdf', ['data' => $reportData, 'monthYear' => $monthYear]);
        return $pdf->download("Sales-Report-{$monthYear}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $targetType = $request->get('target_type', 'month');
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');
        $monthYear = $request->get('month_year', date('Y-m'));

        $reportData = $this->generateReport($reportType, $targetType, $branchId, $employeeId, $monthYear);

        return response()->json(['message' => 'Excel export not implemented yet']);
    }
}
