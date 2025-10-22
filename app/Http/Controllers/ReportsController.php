<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Services\ReportsService;
use App\Services\BaseExportService;
use App\Exports\DailyReportsExport;
use App\Exports\MonthlyReportsExport;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected $reportsService;
    protected $exportService;

    public function __construct(ReportsService $reportsService, BaseExportService $exportService)
    {
        $this->reportsService = $reportsService;
        $this->exportService = $exportService;
    }
    /**
     * Display daily reports
     */
    public function daily(Request $request)
    {
        $reportData = $this->reportsService->getDailyReportData($request);
        $clients = Client::select(['id', 'client_name'])->get();
        $services = Service::select(['id', 'name'])->get();

        return view('reports.daily', array_merge($reportData, compact('clients', 'services')));
    }

    /**
     * Display monthly reports
     */
    public function monthly(Request $request)
    {
        $reportData = $this->reportsService->getMonthlyReportData($request);
        $clients = Client::select(['id', 'client_name'])->get();
        $services = Service::select(['id', 'name'])->get();

        return view('reports.monthly', array_merge($reportData, compact('clients', 'services')));
    }

}
