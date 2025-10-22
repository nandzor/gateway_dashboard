<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');
        $type = $request->input('type');
        $showInactive = $request->input('show_inactive', false);

        $filters = [];
        if ($status) {
            $filters['is_active'] = $status;
        }
        if ($type) {
            $filters['type'] = $type;
        }

        // Show all clients including inactive if requested
        if ($showInactive) {
            $clients = $this->clientService->getAllWithInactive($search, $perPage, $filters);
        } else {
            $clients = $this->clientService->getPaginate($search, $perPage, $filters);
        }

        $statistics = $this->clientService->getStatistics();
        $typeOptions = $this->clientService->getTypeOptions();
        $perPageOptions = $this->clientService->getPerPageOptions();

        return view('clients.index', compact(
            'clients',
            'statistics',
            'typeOptions',
            'perPageOptions',
            'search',
            'perPage',
            'status',
            'type',
            'showInactive'
        ));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $typeOptions = $this->clientService->getTypeOptions();
        $serviceModuleOptions = $this->clientService->getServiceModuleOptions();

        // Generate preview credentials
        $previewCredentials = [
            'ak' => $this->clientService->generateAccessKey(),
            'sk' => $this->clientService->generateSecretKey(),
            'avkey_iv' => $this->clientService->generateAvkeyIv(),
            'avkey_pass' => $this->clientService->generateAvkeyPass(),
        ];

        return view('clients.create', compact('typeOptions', 'serviceModuleOptions', 'previewCredentials'));
    }

    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $data['is_active'] ?? 1;
            $data['is_staging'] = $data['is_staging'] ?? 0;

            // Handle white_list conversion from string to array
            if (isset($data['white_list']) && is_string($data['white_list'])) {
                $data['white_list'] = array_filter(explode(',', $data['white_list']));
            }

            $client = $this->clientService->createClient($data);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Client created successfully.');
        } catch (\Exception $e) {
            Log::error('Client creation failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create client: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client = $this->clientService->getClientWithDetails($client->id);
        if (!$client) {
            abort(404, 'Client not found.');
        }

        // Calculate days since created
        $daysSinceCreated = 0; // Default value
        if ($client->created_at) {
            $createdDate = $client->created_at->startOfDay();
            $currentDate = now()->startOfDay();
            $daysSinceCreated = (int) $createdDate->diffInDays($currentDate);
        }

        return view('clients.show', compact('client', 'daysSinceCreated'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        $typeOptions = $this->clientService->getTypeOptions();
        $serviceModuleOptions = $this->clientService->getServiceModuleOptions();

        return view('clients.edit', compact('client', 'typeOptions', 'serviceModuleOptions'));
    }

    /**
     * Update the specified client.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $data = $request->validated();

            // Handle white_list conversion from string to array
            if (isset($data['white_list']) && is_string($data['white_list'])) {
                $data['white_list'] = array_filter(explode(',', $data['white_list']));
            }

            $this->clientService->updateClient($client, $data);

            return redirect()
                ->route('clients.show', $client)
                ->with('success', 'Client updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update client: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified client (soft delete).
     */
    public function destroy(Client $client)
    {
        try {
            $this->clientService->deleteClient($client);

            return redirect()
                ->route('clients.index')
                ->with('success', 'Client deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete client: ' . $e->getMessage());
        }
    }
}
