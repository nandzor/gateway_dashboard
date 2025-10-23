<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\History;
use App\Models\Service;
use App\Services\HistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    protected $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Display a listing of histories
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = $this->getFilterParameters($request);

        // Get data for view
        $viewData = $this->getViewData($filters);

        return view('histories.index', $viewData);
    }

    /**
     * Get filter parameters from request
     */
    private function getFilterParameters(Request $request): array
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);
        $clientId = $request->get('client_id');
        $serviceId = $request->get('service_id');
        $status = $request->get('status');

        // Build query filters
        $queryFilters = [];
        if ($clientId) $queryFilters['client_id'] = $clientId;
        if ($serviceId) $queryFilters['module_id'] = $serviceId;
        if ($status) $queryFilters['status'] = $status;

        return [
            'search' => $search,
            'per_page' => $perPage,
            'client_id' => $clientId,
            'service_id' => $serviceId,
            'status' => $status,
            'query_filters' => $queryFilters
        ];
    }

    /**
     * Get data for view
     */
    private function getViewData(array $filters): array
    {
        return [
            'histories' => $this->historyService->getAllHistories(
                $filters['search'],
                $filters['per_page'],
                $filters['query_filters']
            ),
            'stats' => $this->historyService->getHistoryStats(),
            'perPageOptions' => $this->historyService->getPerPageOptions(),
            'clients' => Client::select('id', 'client_name')->orderBy('client_name')->get(),
            'services' => Service::select('id', 'name')->orderBy('name')->get(),
            'search' => $filters['search'],
            'perPage' => $filters['per_page'],
            'clientId' => $filters['client_id'],
            'serviceId' => $filters['service_id']
        ];
    }

    /**
     * Display the specified history
     */
    public function show(History $history)
    {
        $history->load(['client', 'service']);

        return view('histories.show', compact('history'));
    }

    /**
     * Export histories to CSV
     */
    public function export(Request $request)
    {
        $filters = [];

        if ($request->has('status')) {
            $filters['status'] = $request->get('status');
        }
        if ($request->has('transaction_type')) {
            $filters['trx_type'] = $request->get('transaction_type');
        }
        if ($request->has('client_id')) {
            $filters['client_id'] = $request->get('client_id');
        }
        if ($request->has('service_id')) {
            $filters['module_id'] = $request->get('service_id');
        }

        try {
            $filepath = $this->historyService->exportHistories($filters);
            $filename = basename($filepath);

            return response()->download($filepath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to export histories: ' . $e->getMessage());
        }
    }

    /**
     * Get histories by client
     */
    public function byClient(Request $request, $clientId)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $histories = $this->historyService->getHistoriesByClient($clientId, $search, $perPage);
        $client = Client::findOrFail($clientId);

        return view('histories.by-client', compact('histories', 'client', 'search', 'perPage'));
    }

    /**
     * Get histories by service
     */
    public function byService(Request $request, $serviceId)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $histories = $this->historyService->getHistoriesByService($serviceId, $search, $perPage);
        $service = Service::findOrFail($serviceId);

        return view('histories.by-service', compact('histories', 'service', 'search', 'perPage'));
    }
}
