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
        $this->searchableFields = ['name', 'code', 'symbol'];
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
     * Delete a currency
     */
    public function deleteCurrency(Currency $currency): bool
    {
        return $this->delete($currency);
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
        return $this->model->select('id', 'name', 'code', 'symbol')
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
        $requiredFields = ['name', 'code', 'symbol'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Field {$field} is required");
            }
        }

        // Validate code length (3 characters)
        if (strlen($data['code']) !== 3) {
            throw new \InvalidArgumentException("Currency code must be exactly 3 characters");
        }

        // Validate code uniqueness (excluding current record for updates)
        $query = $this->model->where('code', $data['code']);
        if (isset($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }
        if ($query->exists()) {
            throw new \InvalidArgumentException("Currency code already exists");
        }

        return $data;
    }
}
