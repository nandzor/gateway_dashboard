# Soft Delete Implementation

## Overview
Soft delete telah diimplementasikan untuk semua module utama dalam sistem Gateway Dashboard. Soft delete memungkinkan data untuk "dihapus" tanpa benar-benar menghapusnya dari database, melainkan hanya menandai record sebagai tidak aktif.

## Modules dengan Soft Delete

### 1. Users Module
- **Model**: `App\Models\User`
- **Status Field**: `is_active` (integer: 1 = active, 0 = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to 0
  - `restore()`: Set `is_active` to 1
  - `isActive()`: Check if user is active
- **Scopes**:
  - `active()`: Only active users
  - `inactive()`: Only inactive users

### 2. Clients Module
- **Model**: `App\Models\Client`
- **Status Field**: `is_active` (integer: 1 = active, 0 = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to 0
  - `restore()`: Set `is_active` to 1
  - `isActive()`: Check if client is active
- **Scopes**:
  - `active()`: Only active clients
  - `inactive()`: Only inactive clients
- **Additional Features**:
  - Redis cache update when soft deleting
  - Service assignments preserved

### 3. Services Module
- **Model**: `App\Models\Service`
- **Status Field**: `is_active` (integer: 1 = active, 0 = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to 0
  - `restore()`: Set `is_active` to 1
  - `isActive()`: Check if service is active
- **Scopes**:
  - `active()`: Only active services
  - `inactive()`: Only inactive services

### 4. Currencies Module
- **Model**: `App\Models\Currency`
- **Status Field**: `is_active` (boolean: true = active, false = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to false
  - `restore()`: Set `is_active` to true
  - `isActive()`: Check if currency is active
- **Scopes**:
  - `active()`: Only active currencies
  - `inactive()`: Only inactive currencies

### 5. Price Customs Module
- **Model**: `App\Models\PriceCustom`
- **Status Field**: `is_active` (boolean: true = active, false = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to false
  - `restore()`: Set `is_active` to true
  - `isActive()`: Check if price custom is active
- **Scopes**:
  - `active()`: Only active price customs
  - `inactive()`: Only inactive price customs

### 6. Price Masters Module
- **Model**: `App\Models\PriceMaster`
- **Status Field**: `is_active` (boolean: true = active, false = inactive)
- **Methods**:
  - `softDelete()`: Set `is_active` to false
  - `restore()`: Set `is_active` to true
  - `isActive()`: Check if price master is active
- **Scopes**:
  - `active()`: Only active price masters
  - `inactive()`: Only inactive price masters

## Service Layer Implementation

### BaseService
- **Method**: `delete(Model $model)`
- **Behavior**: Uses hard delete (calls `$model->delete()`)
- **Note**: Individual services override this for soft delete

### Individual Services
Each service implements soft delete methods:

#### UserService
```php
public function deleteUser(Model $user): bool
{
    /** @var User $user */
    return $user->softDelete();
}

public function restoreUser(Model $user): bool
{
    /** @var User $user */
    return $user->restore();
}
```

#### ClientService
```php
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
```

#### ServiceService
```php
public function deleteService(Service $service): bool
{
    return $service->softDelete();
}

public function restoreService(Service $service): bool
{
    return $service->restore();
}
```

#### CurrencyService
```php
public function deleteCurrency(Currency $currency): bool
{
    return $currency->softDelete();
}

public function restoreCurrency(Currency $currency): bool
{
    return $currency->restore();
}
```

#### PriceCustomService
```php
public function deletePriceCustom(PriceCustom $priceCustom): bool
{
    return $priceCustom->softDelete();
}

public function restorePriceCustom(PriceCustom $priceCustom): bool
{
    return $priceCustom->restore();
}
```

#### PriceMasterService
```php
public function deletePriceMaster(PriceMaster $priceMaster): bool
{
    return $priceMaster->softDelete();
}

public function restorePriceMaster(PriceMaster $priceMaster): bool
{
    return $priceMaster->restore();
}
```

## Controller Implementation

All controllers use the service layer methods for soft delete:

### Example: UserController
```php
public function destroy($id)
{
    $user = $this->userService->findByIdWithInactive($id);
    
    if (!$user) {
        return redirect()->route('users.index')
            ->with('error', 'User not found.');
    }
    
    if ((int)$id === Auth::id()) {
        return redirect()->route('users.index')
            ->with('error', 'You cannot delete your own account.');
    }
    
    $this->userService->deleteUser($user);
    
    return redirect()->route('users.index')
        ->with('success', 'User deactivated successfully.');
}
```

## Success Messages

All controllers show appropriate messages:
- **Success**: "X deactivated successfully."
- **Error**: "Failed to delete X: [error message]"

## Database Queries

### Active Records Only (Default)
```php
// Only active records
$users = User::active()->get();
$clients = Client::active()->get();
$services = Service::active()->get();
$currencies = Currency::active()->get();
$priceCustoms = PriceCustom::active()->get();
$priceMasters = PriceMaster::active()->get();
```

### Including Inactive Records
```php
// All records including inactive
$users = User::withInactive()->get();
$clients = Client::withInactive()->get();
$services = Service::withInactive()->get();
$currencies = Currency::withInactive()->get();
$priceCustoms = PriceCustom::withInactive()->get();
$priceMasters = PriceMaster::withInactive()->get();
```

### Only Inactive Records
```php
// Only inactive records
$users = User::inactive()->get();
$clients = Client::inactive()->get();
$services = Service::inactive()->get();
$currencies = Currency::inactive()->get();
$priceCustoms = PriceCustom::inactive()->get();
$priceMasters = PriceMaster::inactive()->get();
```

## Testing

Soft delete functionality has been tested and verified:

```bash
# Test soft delete
php artisan tinker --execute="
$currency = App\Models\Currency::first();
$currency->softDelete();
$currency->refresh();
echo 'Active: ' . ($currency->is_active ? 'Yes' : 'No');
"
```

## Benefits

1. **Data Preservation**: No data is permanently lost
2. **Audit Trail**: Can track when records were deactivated
3. **Recovery**: Records can be restored if needed
4. **Referential Integrity**: Related records remain intact
5. **Performance**: Faster than hard delete operations
6. **Compliance**: Meets data retention requirements

## Usage Examples

### Soft Delete a Record
```php
$user = User::find(1);
$user->softDelete(); // Sets is_active to 0
```

### Restore a Record
```php
$user = User::find(1);
$user->restore(); // Sets is_active to 1
```

### Check if Record is Active
```php
$user = User::find(1);
if ($user->isActive()) {
    // User is active
}
```

### Query Only Active Records
```php
$activeUsers = User::active()->get();
```

### Query Only Inactive Records
```php
$inactiveUsers = User::inactive()->get();
```

## Notes

- All soft delete operations are reversible
- Redis cache is updated for clients when soft deleting
- Service assignments are preserved during soft delete
- Controllers show "deactivated" instead of "deleted" in success messages
- All models maintain their relationships during soft delete
