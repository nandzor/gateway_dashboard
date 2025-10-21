<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService {
    /**
     * Constructor
     */
    public function __construct() {
        $this->model = new User();
        $this->searchableFields = ['username', 'name', 'email'];
    }

    /**
     * Get base query - only active users (is_active = 1)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getBaseQuery()->where('is_active', 1);
    }

    /**
     * Get all users including inactive ones
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithInactive(?string $search = null, int $perPage = 10)
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->query();

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User {
        /** @var User|null */
        return parent::findById($id);
    }

    /**
     * Create new user
     */
    public function createUser(array $data): User {
        $data['password'] = Hash::make($data['password']);

        // Set is_active to 1 by default if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }

        $user = $this->create($data);

        /** @var User $user */
        return $user;
    }

    /**
     * Update user
     */
    public function updateUser(Model $user, array $data): bool {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->update($user, $data);
    }

    /**
     * Soft delete user by setting is_active to 0
     */
    public function deleteUser(Model $user): bool {
        /** @var User $user */
        return $user->softDelete();
    }

    /**
     * Restore soft deleted user
     */
    public function restoreUser(Model $user): bool {
        /** @var User $user */
        return $user->restore();
    }

    /**
     * Hard delete user (permanent)
     */
    public function hardDeleteUser(Model $user): bool {
        return $user->delete();
    }

    /**
     * Find user by ID including inactive users
     */
    public function findByIdWithInactive(int $id): ?User {
        /** @var User|null */
        return $this->model->find($id);
    }
}
