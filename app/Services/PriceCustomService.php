<?php

namespace App\Services;

use App\Models\PriceCustom;
use App\Models\Service;
use App\Models\Client;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PriceCustomService extends BaseService
{
    public function __construct()
    {
        $this->model = new PriceCustom();
        $this->searchableFields = [];
        $this->orderByColumn = 'created_at';
        $this->orderByDirection = 'desc';
    }

    /**
     * Get all price customs with relationships
     */
    public function getAllPriceCustoms(?string $search = null, int $perPage = 15, array $filters = [])
    {
        $query = $this->getBaseQuery()
            ->with(['service', 'client', 'currency']);

        // Apply filters
        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        // Apply search if provided
        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        // Apply default ordering
        $query = $query->orderBy($this->orderByColumn, $this->orderByDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get price custom by ID with relationships
     */
    public function getPriceCustomById(int $id): ?PriceCustom
    {
        return $this->model->with(['service', 'client', 'currency'])->find($id);
    }

    /**
     * Create new price custom
     */
    public function createPriceCustom(array $data): PriceCustom
    {
        return $this->create($data);
    }

    /**
     * Update price custom
     */
    public function updatePriceCustom(PriceCustom $priceCustom, array $data): bool
    {
        return $this->update($priceCustom, $data);
    }

    /**
     * Delete price custom
     */
    public function deletePriceCustom(PriceCustom $priceCustom): bool
    {
        return $priceCustom->softDelete();
    }

    /**
     * Restore a soft deleted price custom
     */
    public function restorePriceCustom(PriceCustom $priceCustom): bool
    {
        return $priceCustom->restore();
    }

    /**
     * Get all services for dropdown
     */
    public function getServices(): Collection
    {
        return Service::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all clients for dropdown
     */
    public function getClients(): Collection
    {
        return Client::select('id', 'client_name')
            ->where('is_active', true)
            ->orderBy('client_name')
            ->get();
    }

    /**
     * Get all currencies for dropdown
     */
    public function getCurrencies(): Collection
    {
        return Currency::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get price custom statistics
     */
    public function getPriceCustomStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'total_value' => $this->model->sum('price_custom'),
        ];
    }

    /**
     * Get price customs by service
     */
    public function getPriceCustomsByService(int $serviceId, ?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->getBaseQuery()
            ->where('module_id', $serviceId)
            ->with(['service', 'client', 'currency']);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get price customs by client
     */
    public function getPriceCustomsByClient(int $clientId, ?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->getBaseQuery()
            ->where('client_id', $clientId)
            ->with(['service', 'client', 'currency']);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get price customs by currency
     */
    public function getPriceCustomsByCurrency(int $currencyId, ?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->getBaseQuery()
            ->where('currency_id', $currencyId)
            ->with(['service', 'client', 'currency']);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Validate price custom data
     */
    public function validatePriceCustomData(array $data): array
    {
        // Ensure required fields are present
        $requiredFields = ['module_id', 'client_id', 'price_custom', 'currency_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        // Validate numeric fields
        if (!is_numeric($data['price_custom']) || $data['price_custom'] < 0) {
            throw new \InvalidArgumentException("Price must be a positive number");
        }

        // Validate foreign key relationships
        if (!Service::where('id', $data['module_id'])->exists()) {
            throw new \InvalidArgumentException("Selected service does not exist");
        }

        if (!Client::where('id', $data['client_id'])->exists()) {
            throw new \InvalidArgumentException("Selected client does not exist");
        }

        if (!Currency::where('id', $data['currency_id'])->exists()) {
            throw new \InvalidArgumentException("Selected currency does not exist");
        }

        return $data;
    }
}
