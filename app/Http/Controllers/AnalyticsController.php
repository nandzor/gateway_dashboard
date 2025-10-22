<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Services\AnalyticsService;
use App\Services\BaseExportService;
use App\Exports\AnalyticsExport;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $exportService;

    public function __construct(AnalyticsService $analyticsService, BaseExportService $exportService)
    {
        $this->analyticsService = $analyticsService;
        $this->exportService = $exportService;
    }
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $analyticsData = $this->analyticsService->getAnalyticsData($request);
        $clients = Client::select(['id', 'client_name'])->get();
        $services = Service::select(['id', 'name'])->get();

        return view('analytics.index', array_merge($analyticsData, compact('clients', 'services')));
    }

}
