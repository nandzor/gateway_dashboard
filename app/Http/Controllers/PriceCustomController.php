<?php

namespace App\Http\Controllers;

use App\Models\PriceCustom;
use App\Services\PriceCustomService;
use App\Http\Requests\StorePriceCustomRequest;
use App\Http\Requests\UpdatePriceCustomRequest;
use Illuminate\Http\Request;

class PriceCustomController extends Controller
{
    protected $priceCustomService;

    public function __construct(PriceCustomService $priceCustomService)
    {
        $this->priceCustomService = $priceCustomService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $serviceId = $request->get('service_id');
        $clientId = $request->get('client_id');
        $currencyId = $request->get('currency_id');
        $status = $request->get('status');

        $filters = [];
        if ($serviceId) $filters['module_id'] = $serviceId;
        if ($clientId) $filters['client_id'] = $clientId;
        if ($currencyId) $filters['currency_id'] = $currencyId;
        if ($status) $filters['is_active'] = $status;

        $priceCustoms = $this->priceCustomService->getAllPriceCustoms($search, $perPage, $filters);
        $stats = $this->priceCustomService->getPriceCustomStats();
        $services = $this->priceCustomService->getServices();
        $clients = $this->priceCustomService->getClients();
        $currencies = $this->priceCustomService->getCurrencies();
        $perPageOptions = $this->priceCustomService->getPerPageOptions();

        return view('price-customs.index', compact(
            'priceCustoms',
            'stats',
            'search',
            'perPage',
            'serviceId',
            'clientId',
            'currencyId',
            'status',
            'services',
            'clients',
            'currencies',
            'perPageOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = $this->priceCustomService->getServices();
        $clients = $this->priceCustomService->getClients();
        $currencies = $this->priceCustomService->getCurrencies();

        return view('price-customs.create', compact('services', 'clients', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePriceCustomRequest $request)
    {
        try {
            $data = $request->validated();
            $this->priceCustomService->validatePriceCustomData($data);

            $priceCustom = $this->priceCustomService->createPriceCustom($data);

            return redirect()
                ->route('price-customs.index')
                ->with('success', 'Price custom created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create price custom: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceCustom $priceCustom)
    {
        $priceCustom->load(['service', 'client', 'currency']);
        return view('price-customs.show', compact('priceCustom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceCustom $priceCustom)
    {
        $services = $this->priceCustomService->getServices();
        $clients = $this->priceCustomService->getClients();
        $currencies = $this->priceCustomService->getCurrencies();

        return view('price-customs.edit', compact('priceCustom', 'services', 'clients', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePriceCustomRequest $request, PriceCustom $priceCustom)
    {
        try {
            $data = $request->validated();
            $this->priceCustomService->validatePriceCustomData($data);

            $this->priceCustomService->updatePriceCustom($priceCustom, $data);

            return redirect()
                ->route('price-customs.index')
                ->with('success', 'Price custom updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update price custom: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceCustom $priceCustom)
    {
        try {
            $this->priceCustomService->deletePriceCustom($priceCustom);

            return redirect()
                ->route('price-customs.index')
                ->with('success', 'Price custom deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete price custom: ' . $e->getMessage());
        }
    }
}
