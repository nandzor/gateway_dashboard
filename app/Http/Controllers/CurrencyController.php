<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CurrencyService;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $filters = [];
        if ($request->has('is_active')) {
            $filters['is_active'] = $request->get('is_active');
        }

        $currencies = $this->currencyService->getAllCurrencies($search, $perPage, $filters);
        $stats = $this->currencyService->getCurrencyStats();

        return view('currencies.index', compact(
            'currencies',
            'stats',
            'search',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request)
    {
        try {
            $data = $request->validated();
            $this->currencyService->validateCurrencyData($data);

            $currency = $this->currencyService->createCurrency($data);

            return redirect()
                ->route('currencies.index')
                ->with('success', 'Currency created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create currency: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        try {
            $data = $request->validated();
            $data['id'] = $currency->id; // Add ID for uniqueness validation
            $this->currencyService->validateCurrencyData($data);

            $this->currencyService->updateCurrency($currency, $data);

            return redirect()
                ->route('currencies.index')
                ->with('success', 'Currency updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update currency: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        try {
            $this->currencyService->deleteCurrency($currency);

            return redirect()
                ->route('currencies.index')
                ->with('success', 'Currency deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete currency: ' . $e->getMessage());
        }
    }
}
