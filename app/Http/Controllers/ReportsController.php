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

    /**
     * Export daily reports
     */
    public function exportDaily(Request $request)
    {
        $format = $request->input('format', 'excel');
        $reportData = $this->reportsService->getDailyReportData($request);

        // Prepare export data
        $exportData = [
            'summary' => [
                'Total Transactions' => $reportData['totalTransactions'],
                'Total Revenue' => $reportData['totalRevenue'],
                'Total Duration' => $reportData['totalDuration'],
                'Unique Users' => $reportData['uniqueUsers'],
                'Unique Clients' => $reportData['uniqueClients'],
                'Success Rate' => $reportData['successRate'] . '%',
                'Avg Transaction Value' => $reportData['avgTransactionValue'],
                'Peak Hour' => $reportData['peakHour'],
                'Busiest Service' => $reportData['busiestService'],
                'Top Client' => $reportData['topClient'],
            ],
            'transaction_types' => $reportData['transactionTypes'],
            'client_types' => $reportData['clientTypes'],
            'top_clients' => $reportData['topClients'],
            'top_services' => $reportData['topServices'],
            'hourly_trends' => $reportData['hourlyTrends'],
            'status_breakdown' => $reportData['statusBreakdown'],
            'charge_breakdown' => $reportData['chargeBreakdown'],
            'date' => $reportData['date'],
            'client_id' => $reportData['clientId'],
            'service_id' => $reportData['serviceId'],
        ];

        $fileName = $this->exportService->generateFileName('Daily_Report_' . $reportData['date']);

        // Export using service
        return $this->exportService->export(
            $format,
            new DailyReportsExport($exportData),
            'reports.daily-pdf',
            $exportData,
            $fileName
        );
    }

    /**
     * Export monthly reports
     */
    public function exportMonthly(Request $request)
    {
        $format = $request->input('format', 'excel');
        $reportData = $this->reportsService->getMonthlyReportData($request);

        // Prepare export data
        $exportData = [
            'summary' => [
                'Total Transactions' => $reportData['totalTransactions'],
                'Total Revenue' => $reportData['totalRevenue'],
                'Total Duration' => $reportData['totalDuration'],
                'Unique Users' => $reportData['uniqueUsers'],
                'Unique Clients' => $reportData['uniqueClients'],
                'Avg Transactions/Day' => $reportData['avgTransactionsPerDay'],
                'Avg Revenue/Day' => $reportData['avgRevenuePerDay'],
                'Avg Duration/Day' => $reportData['avgDurationPerDay'],
            ],
            'transaction_types' => $reportData['transactionTypes'],
            'client_types' => $reportData['clientTypes'],
            'top_clients' => $reportData['topClients'],
            'top_services' => $reportData['topServices'],
            'daily_trends' => $reportData['dailyTrends'],
            'weekly_trends' => $reportData['weeklyTrends'],
            'status_breakdown' => $reportData['statusBreakdown'],
            'charge_breakdown' => $reportData['chargeBreakdown'],
            'month' => $reportData['month'],
            'clientId' => $reportData['clientId'],
            'serviceId' => $reportData['serviceId'],
        ];

        $fileName = $this->exportService->generateFileName('Monthly_Report_' . $reportData['month']);

        // Export using service
        return $this->exportService->export(
            $format,
            new MonthlyReportsExport($exportData),
            'reports.monthly-pdf',
            $exportData,
            $fileName
        );
    }

}
