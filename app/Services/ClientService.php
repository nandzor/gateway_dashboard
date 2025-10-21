<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class ClientService extends BaseService
{
    /**
     * ClientService constructor.
     */
    public function __construct()
    {
        $this->model = new Client();
        $this->searchableFields = ['client_name', 'contact', 'address'];
        $this->orderByColumn = 'created_at';
        $this->orderByDirection = 'desc';
    }

    /**
     * Get base query - only active clients (is_active = 1)
     */
    protected function getBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getBaseQuery()->where('is_active', 1);
    }

    /**
     * Get all clients including inactive ones
     */
    public function getAllWithInactive(?string $search = null, int $perPage = 10, array $filters = [])
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        // Apply search if provided
        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Find client by ID including inactive ones
     */
    public function findByIdWithInactive(int $id): ?Client
    {
        /** @var Client|null */
        return $this->model->find($id);
    }

    /**
     * Create client
     */
    public function createClient(array $data): Client
    {
        // Set is_active to 1 by default if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }

        // Set is_staging to 0 by default if not provided
        if (!isset($data['is_staging'])) {
            $data['is_staging'] = 0;
        }

        $client = $this->create($data);

        /** @var Client $client */

        // Auto-create initial balance for the client
        $this->createInitialBalance($client);

        // Create Redis cache for client
        $this->createClientRedisCache($client);

        return $client;
    }

    /**
     * Create initial balance for client
     */
    public function createInitialBalance(Client $client): \App\Models\Balance
    {
        return \App\Models\Balance::create([
            'client_id' => $client->id,
            'balance' => 0.000,
            'quota' => 0,
        ]);
    }

    /**
     * Update client
     */
    public function updateClient(Model $client, array $data): bool
    {
        $result = $this->update($client, $data);

        if ($result) {
            // Update Redis cache for client
            $this->updateClientRedisCache($client);
        }

        return $result;
    }

    /**
     * Soft delete client by setting is_active to 0
     */
    public function deleteClient(Model $client): bool
    {
        /** @var Client $client */
        $result = $client->softDelete();

        if ($result) {
            // Update Redis cache - set is_active to 0
            $this->updateClientRedisCache($client);
        }

        return $result;
    }

    /**
     * Restore soft deleted client
     */
    public function restoreClient(Model $client): bool
    {
        /** @var Client $client */
        $result = $client->restore();

        if ($result) {
            // Update Redis cache - set is_active to 1
            $this->updateClientRedisCache($client);
        }

        return $result;
    }

    /**
     * Get client with related data
     */
    public function getClientWithDetails(int $id): ?Client
    {
        return Client::with(['balances' => function($query) {
            $query->latest();
        }])->find($id);
        // TODO: Add other relationships when models are created
        // return Client::with(['credentials', 'balances', 'histories', 'services', 'whitelistIps'])
        //     ->find($id);
    }

    /**
     * Get active clients only
     */
    public function getActiveClients()
    {
        return Client::active()
            ->orderBy('client_name', 'asc')
            ->get();
    }

    /**
     * Get clients by type
     */
    public function getByType(int $type)
    {
        return Client::where('type', $type)
            ->active()
            ->orderBy('client_name', 'asc')
            ->get();
    }

    /**
     * Get staging clients
     */
    public function getStagingClients()
    {
        return Client::staging()
            ->active()
            ->orderBy('client_name', 'asc')
            ->get();
    }

    /**
     * Get production clients
     */
    public function getProductionClients()
    {
        return Client::production()
            ->active()
            ->orderBy('client_name', 'asc')
            ->get();
    }

    /**
     * Get client statistics
     */
    public function getStatistics(): array
    {
        $total = Client::count();
        $active = Client::active()->count();
        $inactive = Client::inactive()->count();
        $staging = Client::staging()->active()->count();
        $production = Client::production()->active()->count();

        // Count by type
        $typeStats = [];
        for ($i = 1; $i <= 4; $i++) {
            $typeStats["type_{$i}"] = Client::where('type', $i)->active()->count();
        }

        return [
            'total_clients' => $total,
            'active_clients' => $active,
            'inactive_clients' => $inactive,
            'staging_clients' => $staging,
            'production_clients' => $production,
            'type_statistics' => $typeStats,
        ];
    }

    /**
     * Get client type options
     */
    public function getTypeOptions(): array
    {
        return [
            1 => 'Individual',
            2 => 'Corporate',
            3 => 'Government',
            4 => 'NGO',
        ];
    }

    /**
     * Get service module options
     */
    public function getServiceModuleOptions(): array
    {
        return [
            1 => 'Basic Service',
            2 => 'Premium Service',
            3 => 'Enterprise Service',
            4 => 'Custom Service',
        ];
    }

    /**
     * Get current balance for client
     */
    public function getCurrentBalance(Client $client): ?\App\Models\Balance
    {
        return $client->balances()->latest()->first();
    }

    /**
     * Add balance to client
     */
    public function addBalance(Client $client, float $amount, int $quota = 0): \App\Models\Balance
    {
        $currentBalance = $this->getCurrentBalance($client);

        if ($currentBalance) {
            $currentBalance->addBalance($amount);
            if ($quota > 0) {
                $currentBalance->addQuota($quota);
            }

            // Update Redis cache with new balance
            $this->updateClientRedisCache($client);

            return $currentBalance;
        } else {
            // Create new balance if none exists
            $balance = \App\Models\Balance::create([
                'client_id' => $client->id,
                'balance' => $amount,
                'quota' => $quota,
            ]);

            // Update Redis cache with new balance
            $this->updateClientRedisCache($client);

            return $balance;
        }
    }

    /**
     * Subtract balance from client
     */
    public function subtractBalance(Client $client, float $amount): ?\App\Models\Balance
    {
        $currentBalance = $this->getCurrentBalance($client);

        if ($currentBalance) {
            $currentBalance->subtractBalance($amount);

            // Update Redis cache with new balance
            $this->updateClientRedisCache($client);

            return $currentBalance;
        }

        return null;
    }

    /**
     * Create Redis cache for client
     */
    public function createClientRedisCache(Client $client): void
    {
        $redisKey = $this->getClientRedisKey($client);
        $redisData = $this->buildClientRedisData($client);

        Redis::set($redisKey, json_encode($redisData));
    }

    /**
     * Update Redis cache for client
     */
    public function updateClientRedisCache(Client $client): void
    {
        $redisKey = $this->getClientRedisKey($client);
        $redisData = $this->buildClientRedisData($client);

        Redis::set($redisKey, json_encode($redisData));
    }

    /**
     * Get Redis key for client
     */
    public function getClientRedisKey(Client $client): string
    {
        return $client->id . ':' . $client->ak . '+' . $client->sk;
    }

    /**
     * Build Redis data structure for client
     */
    public function buildClientRedisData(Client $client): array
    {
        // Get service IDs from relationship
        $serviceAllow = $client->services()->pluck('service_id')->toArray();

        // Parse white_list from string to array
        $whiteList = ['*']; // Default allow all
        if ($client->white_list) {
            $whiteList = json_decode($client->white_list, true) ?? ['*'];
        }

        // Get current balance to determine prepaid_allow
        $currentBalance = $this->getCurrentBalance($client);
        $prepaidAllow = 1; // Default prepaid
        if ($currentBalance && $currentBalance->balance <= 0) {
            $prepaidAllow = 0;
        }

        return [
            'type' => 1, // Default prepaid (1:prepaid, 2:postpaid)
            'is_active' => $client->is_active,
            'prepaid_allow' => $prepaidAllow,
            'client_id' => $client->id,
            'service_allow' => $serviceAllow,
            'white_list' => $whiteList,
            'module_40' => [
                'Iv' => '',
                'Pass' => '',
                'Bearer' => ''
            ],
            'avkey' => [
                'Iv' => $client->avkey_iv ?? '',
                'Pass' => $client->avkey_pass ?? ''
            ]
        ];
    }

    /**
     * Update prepaid_allow in Redis cache
     */
    public function updatePrepaidAllow(Client $client, int $prepaidAllow): void
    {
        $redisKey = $this->getClientRedisKey($client);
        $redisData = $this->buildClientRedisData($client);
        $redisData['prepaid_allow'] = $prepaidAllow;

        Redis::set($redisKey, json_encode($redisData));
    }

    /**
     * Get client data from Redis
     */
    public function getClientFromRedis(Client $client): ?array
    {
        $redisKey = $this->getClientRedisKey($client);
        $data = Redis::get($redisKey);

        return $data ? json_decode($data, true) : null;
    }

    /**
     * Delete client from Redis
     */
    public function deleteClientFromRedis(Client $client): void
    {
        $redisKey = $this->getClientRedisKey($client);
        Redis::del($redisKey);
    }

    /**
     * Assign services to client
     */
    public function assignServices(Client $client, array $serviceIds): bool
    {
        try {
            // Sync services (remove old, add new)
            $client->services()->sync($serviceIds);

            // Update Redis cache with new service_allow
            $this->updateClientRedisCache($client);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Unassign service from client
     */
    public function unassignService(Client $client, int $serviceId): bool
    {
        try {
            // Detach specific service
            $client->services()->detach($serviceId);

            // Update Redis cache
            $this->updateClientRedisCache($client);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get assigned services for client
     */
    public function getAssignedServices(Client $client): \Illuminate\Database\Eloquent\Collection
    {
        return $client->services()->get();
    }

    /**
     * Get available services for assignment
     */
    public function getAvailableServices(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Service::active()->get();
    }

    /**
     * Check if service is assigned to client
     */
    public function isServiceAssigned(Client $client, int $serviceId): bool
    {
        return $client->services()->where('service_id', $serviceId)->exists();
    }
}
