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
        return Service::create($data);
    }

    /**
     * Update service
     */
    public function updateService(Service $service, array $data): bool
    {
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
     * Get service type options
     */
    public function getServiceTypeOptions(): array
    {
        return [
            1 => 'Internal',
            2 => 'External',
        ];
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

        if (empty($data['type'])) {
            $errors['type'] = 'Service type is required';
        }

        if (!in_array($data['type'], [1, 2, 3, 4])) {
            $errors['type'] = 'Invalid service type';
        }

        return $errors;
    }
}
