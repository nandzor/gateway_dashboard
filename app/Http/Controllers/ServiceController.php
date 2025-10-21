<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServiceService;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        if ($search) {
            $services = $this->serviceService->searchServices($search, $perPage);
        } else {
            $services = $this->serviceService->getAllServices($perPage);
        }

        $stats = $this->serviceService->getServiceStats();

        return view('services.index', compact('services', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $typeOptions = $this->serviceService->getServiceTypeOptions();
        return view('services.create', compact('typeOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            $service = $this->serviceService->createService($request->validated());

            return redirect()
                ->route('services.index')
                ->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service = $this->serviceService->getServiceWithClients($service->id);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $typeOptions = $this->serviceService->getServiceTypeOptions();
        return view('services.edit', compact('service', 'typeOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            $this->serviceService->updateService($service, $request->validated());

            return redirect()
                ->route('services.index')
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $this->serviceService->deleteService($service);

            return redirect()
                ->route('services.index')
                ->with('success', 'Service deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to deactivate service: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified resource.
     */
    public function restore(Service $service)
    {
        try {
            $this->serviceService->restoreService($service);

            return redirect()
                ->route('services.index')
                ->with('success', 'Service restored successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to restore service: ' . $e->getMessage());
        }
    }

    /**
     * Get services by type (API endpoint)
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');
        $services = $this->serviceService->getServicesByType($type);

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get service statistics (API endpoint)
     */
    public function getStats()
    {
        $stats = $this->serviceService->getServiceStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
