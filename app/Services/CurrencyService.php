<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CurrencyService extends BaseService
{
    public function __construct()
    {
        $this->model = new Currency();
        $this->searchableFields = ['name', 'symbol'];
        $this->orderByColumn = 'name';
        $this->orderByDirection = 'asc';
    }

    /**
     * Get all currencies with pagination and optional search/filters
     */
    public function getAllCurrencies(?string $search = null, int $perPage = 15, array $filters = [])
    {
        $query = $this->getBaseQuery();

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
     * Get a single currency by ID
     */
    public function getCurrencyById(int $id): ?Currency
    {
        return $this->findById($id);
    }

    /**
     * Create a new currency
     */
    public function createCurrency(array $data): Currency
    {
        return $this->create($data);
    }

    /**
     * Update an existing currency
     */
    public function updateCurrency(Currency $currency, array $data): bool
    {
        return $this->update($currency, $data);
    }

    /**
     * Delete a currency (soft delete)
     */
    public function deleteCurrency(Currency $currency): bool
    {
        return $currency->softDelete();
    }

    /**
     * Restore a soft deleted currency
     */
    public function restoreCurrency(Currency $currency): bool
    {
        return $currency->restore();
    }

    /**
     * Get currency statistics
     */
    public function getCurrencyStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
        ];
    }

    /**
     * Get all active currencies for dropdown
     */
    public function getActiveCurrencies(): Collection
    {
        return $this->model->select('id', 'name', 'symbol')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate currency data
     */
    public function validateCurrencyData(array $data): array
    {
        // Ensure required fields are present
        $requiredFields = ['name', 'symbol'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        return $data;
    }
}
