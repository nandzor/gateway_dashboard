<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientServiceAssignmentController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display service assignments for a client
     */
    public function index(Client $client)
    {
        $assignedServices = $this->clientService->getAssignedServices($client);
        $availableServices = $this->clientService->getAvailableServices();

        return view('client-service-assignments.index', compact('client', 'assignedServices', 'availableServices'));
    }

    /**
     * Show the form for assigning services to client
     */
    public function create(Client $client)
    {
        $assignedServices = $this->clientService->getAssignedServices($client);
        $availableServices = $this->clientService->getAvailableServices();

        return view('client-service-assignments.create', compact('client', 'assignedServices', 'availableServices'));
    }

    /**
     * Store service assignments for client
     */
    public function store(Request $request, Client $client)
    {
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'integer|exists:services,id',
        ]);

        try {
            $serviceIds = $request->input('service_ids', []);
            $result = $this->clientService->assignServices($client, $serviceIds);

            if ($result) {
                return redirect()
                    ->route('client-service-assignments.index', $client)
                    ->with('success', 'Services assigned successfully.');
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Failed to assign services.');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to assign services: ' . $e->getMessage());
        }
    }

    /**
     * Remove service assignment from client
     */
    public function destroy(Client $client, $serviceId)
    {
        try {
            $result = $this->clientService->unassignService($client, $serviceId);

            if ($result) {
                return redirect()
                    ->route('client-service-assignments.index', $client)
                    ->with('success', 'Service unassigned successfully.');
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Failed to unassign service.');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to unassign service: ' . $e->getMessage());
        }
    }

    /**
     * Get assigned services for client (API endpoint)
     */
    public function getAssignedServices(Client $client)
    {
        $assignedServices = $this->clientService->getAssignedServices($client);

        return response()->json([
            'success' => true,
            'data' => $assignedServices
        ]);
    }

    /**
     * Get available services (API endpoint)
     */
    public function getAvailableServices()
    {
        $availableServices = $this->clientService->getAvailableServices();

        return response()->json([
            'success' => true,
            'data' => $availableServices
        ]);
    }
}
