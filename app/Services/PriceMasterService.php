<?php

namespace App\Services;

use App\Models\PriceMaster;
use App\Models\Service;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PriceMasterService extends BaseService
{
    public function __construct()
    {
        $this->model = new PriceMaster();
        $this->searchableFields = ['note'];
        $this->orderByColumn = 'created_at';
        $this->orderByDirection = 'desc';
    }

    /**
     * Get all price masters with relationships
     */
    public function getAllPriceMasters(?string $search = null, int $perPage = 15, array $filters = [])
    {
        $query = $this->getBaseQuery()
            ->with(['service', 'currency']);

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
     * Get price master by ID with relationships
     */
    public function getPriceMasterById(int $id): ?PriceMaster
    {
        return $this->model->with(['service', 'currency'])->find($id);
    }

    /**
     * Create new price master
     */
    public function createPriceMaster(array $data): PriceMaster
    {
        return $this->create($data);
    }

    /**
     * Update price master
     */
    public function updatePriceMaster(PriceMaster $priceMaster, array $data): bool
    {
        return $this->update($priceMaster, $data);
    }

    /**
     * Delete price master
     */
    public function deletePriceMaster(PriceMaster $priceMaster): bool
    {
        return $priceMaster->softDelete();
    }

    /**
     * Restore a soft deleted price master
     */
    public function restorePriceMaster(PriceMaster $priceMaster): bool
    {
        return $priceMaster->restore();
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
     * Get price master statistics
     */
    public function getPriceMasterStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'total_value' => $this->model->sum('price_default'),
        ];
    }

    /**
     * Get price masters by service
     */
    public function getPriceMastersByService(int $serviceId, ?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->getBaseQuery()
            ->where('module_id', $serviceId)
            ->with(['service', 'currency']);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get price masters by currency
     */
    public function getPriceMastersByCurrency(int $currencyId, ?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->getBaseQuery()
            ->where('currency_id', $currencyId)
            ->with(['service', 'currency']);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Validate price master data
     */
    public function validatePriceMasterData(array $data): array
    {
        // Ensure required fields are present
        $requiredFields = ['module_id', 'price_default', 'currency_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        // Validate numeric fields
        if (!is_numeric($data['price_default']) || $data['price_default'] < 0) {
            throw new \InvalidArgumentException("Price must be a positive number");
        }

        // Validate foreign key relationships
        if (!Service::where('id', $data['module_id'])->exists()) {
            throw new \InvalidArgumentException("Selected service does not exist");
        }

        if (!Currency::where('id', $data['currency_id'])->exists()) {
            throw new \InvalidArgumentException("Selected currency does not exist");
        }

        return $data;
    }
}
