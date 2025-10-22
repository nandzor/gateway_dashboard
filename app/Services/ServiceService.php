<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceService
{
    /**
     * Get all services with pagination
     */
    public function getAllServices(int $perPage = 15): LengthAwarePaginator
    {
        return Service::orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get active services
     */
    public function getActiveServices(): Collection
    {
        return Service::active()->orderBy('name')->get();
    }

    /**
     * Get inactive services
     */
    public function getInactiveServices(): Collection
    {
        return Service::inactive()->orderBy('name')->get();
    }

    /**
     * Get service by ID
     */
    public function getServiceById(int $id): ?Service
    {
        return Service::find($id);
    }

    /**
     * Create new service
     */
    public function createService(array $data): Service
    {
        // Set default values for removed fields
        $data['type'] = $data['type'] ?? 1; // Default to Internal
        $data['is_alert_zero'] = $data['is_alert_zero'] ?? 0; // Default to No alert

        return Service::create($data);
    }

    /**
     * Update service
     */
    public function updateService(Service $service, array $data): bool
    {
        // Set default values for removed fields if not provided
        $data['type'] = $data['type'] ?? $service->type ?? 1; // Keep existing or default to Internal
        $data['is_alert_zero'] = $data['is_alert_zero'] ?? $service->is_alert_zero ?? 0; // Keep existing or default to No alert

        return $service->update($data);
    }

    /**
     * Soft delete service
     */
    public function deleteService(Service $service): bool
    {
        return $service->softDelete();
    }

    /**
     * Restore service
     */
    public function restoreService(Service $service): bool
    {
        return $service->restore();
    }

    /**
     * Get service with assigned clients
     */
    public function getServiceWithClients(int $id): ?Service
    {
        return Service::with('clients')->find($id);
    }

    /**
     * Get service statistics
     */
    public function getServiceStats(): array
    {
        return [
            'total' => Service::count(),
            'active' => Service::active()->count(),
            'inactive' => Service::inactive()->count(),
        ];
    }

    /**
     * Search services
     */
    public function searchServices(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return Service::where('name', 'like', "%{$query}%")
            ->orWhere('type', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get services by type
     */
    public function getServicesByType(int $type): Collection
    {
        return Service::where('type', $type)
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate service data
     */
    public function validateServiceData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Service name is required';
        }

        return $errors;
    }
}
