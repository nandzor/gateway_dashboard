<?php

namespace App\Http\Controllers;

use App\Models\PriceMaster;
use App\Services\PriceMasterService;
use App\Http\Requests\StorePriceMasterRequest;
use App\Http\Requests\UpdatePriceMasterRequest;
use Illuminate\Http\Request;

class PriceMasterController extends Controller
{
    protected $priceMasterService;

    public function __construct(PriceMasterService $priceMasterService)
    {
        $this->priceMasterService = $priceMasterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $serviceId = $request->get('service_id');
        $currencyId = $request->get('currency_id');
        $status = $request->get('status');

        $filters = [];
        if ($serviceId) $filters['module_id'] = $serviceId;
        if ($currencyId) $filters['currency_id'] = $currencyId;
        if ($status) $filters['is_active'] = $status;

        $priceMasters = $this->priceMasterService->getAllPriceMasters($search, $perPage, $filters);
        $stats = $this->priceMasterService->getPriceMasterStats();
        $services = $this->priceMasterService->getServices();
        $currencies = $this->priceMasterService->getCurrencies();
        $perPageOptions = $this->priceMasterService->getPerPageOptions();

        return view('price-masters.index', compact(
            'priceMasters',
            'stats',
            'search',
            'perPage',
            'serviceId',
            'currencyId',
            'status',
            'services',
            'currencies',
            'perPageOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = $this->priceMasterService->getServices();
        $clients = $this->priceMasterService->getClients();
        $currencies = $this->priceMasterService->getCurrencies();

        return view('price-masters.create', compact('services', 'clients', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePriceMasterRequest $request)
    {
        try {
            $data = $request->validated();
            $this->priceMasterService->validatePriceMasterData($data);

            $priceMaster = $this->priceMasterService->createPriceMaster($data);

            return redirect()
                ->route('price-masters.index')
                ->with('success', 'Price master created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create price master: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceMaster $priceMaster)
    {
        $priceMaster->load(['service', 'currency']);
        return view('price-masters.show', compact('priceMaster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceMaster $priceMaster)
    {
        $services = $this->priceMasterService->getServices();
        $clients = $this->priceMasterService->getClients();
        $currencies = $this->priceMasterService->getCurrencies();

        return view('price-masters.edit', compact('priceMaster', 'services', 'clients', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePriceMasterRequest $request, PriceMaster $priceMaster)
    {
        try {
            $data = $request->validated();
            $this->priceMasterService->validatePriceMasterData($data);

            $this->priceMasterService->updatePriceMaster($priceMaster, $data);

            return redirect()
                ->route('price-masters.index')
                ->with('success', 'Price master updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update price master: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceMaster $priceMaster)
    {
        try {
            $this->priceMasterService->deletePriceMaster($priceMaster);

            return redirect()
                ->route('price-masters.index')
                ->with('success', 'Price master deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete price master: ' . $e->getMessage());
        }
    }
}
