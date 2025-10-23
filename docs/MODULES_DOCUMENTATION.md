# Modules Documentation

## Overview
This document provides comprehensive documentation for all modules in the Gateway Dashboard application, including both web and API endpoints.

## Table of Contents
1. [User Management Module](#user-management-module)
2. [Client Management Module](#client-management-module)
3. [Service Management Module](#service-management-module)
4. [Currency Management Module](#currency-management-module)
5. [Price Master Module](#price-master-module)
6. [Price Custom Module](#price-custom-module)
7. [Balance Management Module](#balance-management-module)
8. [Transaction History Module](#transaction-history-module)
9. [Reports Module](#reports-module)
10. [Dashboard Module](#dashboard-module)

---

## User Management Module

### Overview
Manages user authentication, authorization, and role-based access control.

### Database Schema
```sql
users:
- id (bigint, primary key)
- username (varchar, unique)
- name (varchar)
- email (varchar, unique)
- password (varchar, hashed)
- role (varchar: admin, operator, viewer)
- is_active (integer: 1=active, 0=inactive)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /users | index | List all users |
| GET | /users/create | create | Show create form |
| POST | /users | store | Store new user |
| GET | /users/{user} | show | Show user details |
| GET | /users/{user}/edit | edit | Show edit form |
| PUT/PATCH | /users/{user} | update | Update user |
| DELETE | /users/{user} | destroy | Soft delete user |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/users | Get all users |
| POST | /api/users | Create user |
| GET | /api/users/{id} | Get user by ID |
| PUT | /api/users/{id} | Update user |
| DELETE | /api/users/{id} | Soft delete user |

### Features
- **Role-based Access**: Admin, Operator, Viewer
- **Soft Delete**: Users can be deactivated/reactivated
- **Search & Filter**: By role, status, name, email
- **Password Hashing**: Secure password storage
- **Self-protection**: Users cannot delete themselves

### Service Methods
```php
// UserService
public function createUser(array $data): User
public function updateUser(User $user, array $data): bool
public function deleteUser(User $user): bool
public function restoreUser(User $user): bool
public function getAllWithInactive(string $search = null, int $perPage = 10): LengthAwarePaginator
public function getPaginate(string $search = null, int $perPage = 10): LengthAwarePaginator
```

---

## Client Management Module

### Overview
Manages client information, credentials, and service assignments.

### Database Schema
```sql
clients:
- id (bigint, primary key)
- client_name (varchar)
- address (text)
- contact (varchar)
- type (integer: 1=prepaid, 2=postpaid)
- ak (varchar, API Key)
- sk (varchar, Secret Key)
- avkey_iv (varchar, AVKey IV)
- avkey_pass (varchar, AVKey Password)
- service_module (bigint, foreign key to services)
- is_active (integer: 1=active, 0=inactive)
- service_allow (json, array of service IDs)
- white_list (json, array of IP addresses)
- module_40 (json, module configuration)
- is_staging (integer: 1=staging, 0=production)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /clients | index | List all clients |
| GET | /clients/create | create | Show create form |
| POST | /clients | store | Store new client |
| GET | /clients/{client} | show | Show client details |
| GET | /clients/{client}/edit | edit | Show edit form |
| PUT/PATCH | /clients/{client} | update | Update client |
| DELETE | /clients/{client} | destroy | Soft delete client |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/clients | Get all clients |
| POST | /api/clients | Create client |
| GET | /api/clients/{id} | Get client by ID |
| PUT | /api/clients/{id} | Update client |
| DELETE | /api/clients/{id} | Soft delete client |

### Features
- **Client Types**: Prepaid and Postpaid
- **Credential Management**: AK, SK, AVKey with encryption
- **Service Assignment**: Many-to-many relationship with services
- **Redis Caching**: Client data cached for performance
- **Auto Balance Creation**: Balance record created on client creation
- **Soft Delete**: Clients can be deactivated/reactivated

### Service Methods
```php
// ClientService
public function createClient(array $data): Client
public function updateClient(Client $client, array $data): bool
public function deleteClient(Client $client): bool
public function restoreClient(Client $client): bool
public function assignServices(Client $client, array $serviceIds): bool
public function updateClientRedisCache(Client $client): void
public function getClientRedisKey(Client $client): string
```

---

## Service Management Module

### Overview
Manages internal and external services available in the system.

### Database Schema
```sql
services:
- id (bigint, primary key)
- name (varchar)
- type (integer: 1=internal, 2=external)
- is_active (integer: 1=active, 0=inactive)
- is_alert_zero (integer: 1=alert, 0=no alert)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /services | index | List all services |
| GET | /services/create | create | Show create form |
| POST | /services | store | Store new service |
| GET | /services/{service} | show | Show service details |
| GET | /services/{service}/edit | edit | Show edit form |
| PUT/PATCH | /services/{service} | update | Update service |
| DELETE | /services/{service} | destroy | Soft delete service |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/services | Get all services |
| POST | /api/services | Create service |
| GET | /api/services/{id} | Get service by ID |
| PUT | /api/services/{id} | Update service |
| DELETE | /api/services/{id} | Soft delete service |

### Features
- **Service Types**: Internal and External
- **Client Assignment**: Many-to-many relationship with clients
- **Alert System**: Zero balance alert configuration
- **Soft Delete**: Services can be deactivated/reactivated
- **Search & Filter**: By type, status, name

### Service Methods
```php
// ServiceService
public function createService(array $data): Service
public function updateService(Service $service, array $data): bool
public function deleteService(Service $service): bool
public function restoreService(Service $service): bool
public function getServicesForDropdown(): Collection
public function searchServices(string $search, int $perPage = 10): LengthAwarePaginator
```

---

## Currency Management Module

### Overview
Manages supported currencies for pricing and transactions.

### Database Schema
```sql
currencies:
- id (bigint, primary key)
- name (varchar)
- code (varchar, unique)
- symbol (varchar)
- is_active (boolean: true=active, false=inactive)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /currencies | index | List all currencies |
| GET | /currencies/create | create | Show create form |
| POST | /currencies | store | Store new currency |
| GET | /currencies/{currency} | show | Show currency details |
| GET | /currencies/{currency}/edit | edit | Show edit form |
| PUT/PATCH | /currencies/{currency} | update | Update currency |
| DELETE | /currencies/{currency} | destroy | Soft delete currency |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/currencies | Get all currencies |
| POST | /api/currencies | Create currency |
| GET | /api/currencies/{id} | Get currency by ID |
| PUT | /api/currencies/{id} | Update currency |
| DELETE | /api/currencies/{id} | Soft delete currency |

### Features
- **Multi-currency Support**: Support for multiple currencies
- **Currency Codes**: Standard currency codes (USD, EUR, IDR, etc.)
- **Symbol Display**: Currency symbols for UI display
- **Soft Delete**: Currencies can be deactivated/reactivated
- **Pricing Integration**: Used in price master and custom pricing

### Service Methods
```php
// CurrencyService
public function createCurrency(array $data): Currency
public function updateCurrency(Currency $currency, array $data): bool
public function deleteCurrency(Currency $currency): bool
public function restoreCurrency(Currency $currency): bool
public function getCurrenciesForDropdown(): Collection
public function getCurrencyStats(): array
```

---

## Price Master Module

### Overview
Manages default pricing for services across different currencies.

### Database Schema
```sql
price_masters:
- id (bigint, primary key)
- module_id (bigint, foreign key to services)
- price_default (decimal)
- is_active (boolean: true=active, false=inactive)
- note (text)
- currency_id (bigint, foreign key to currencies)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /price-masters | index | List all price masters |
| GET | /price-masters/create | create | Show create form |
| POST | /price-masters | store | Store new price master |
| GET | /price-masters/{priceMaster} | show | Show price master details |
| GET | /price-masters/{priceMaster}/edit | edit | Show edit form |
| PUT/PATCH | /price-masters/{priceMaster} | update | Update price master |
| DELETE | /price-masters/{priceMaster} | destroy | Soft delete price master |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/price-masters | Get all price masters |
| POST | /api/price-masters | Create price master |
| GET | /api/price-masters/{id} | Get price master by ID |
| PUT | /api/price-masters/{id} | Update price master |
| DELETE | /api/price-masters/{id} | Soft delete price master |

### Features
- **Default Pricing**: Base pricing for services
- **Multi-currency**: Different prices per currency
- **Service Integration**: Linked to specific services
- **Soft Delete**: Price masters can be deactivated/reactivated
- **Notes**: Additional information about pricing

### Service Methods
```php
// PriceMasterService
public function createPriceMaster(array $data): PriceMaster
public function updatePriceMaster(PriceMaster $priceMaster, array $data): bool
public function deletePriceMaster(PriceMaster $priceMaster): bool
public function restorePriceMaster(PriceMaster $priceMaster): bool
public function getServicesForDropdown(): Collection
public function getCurrenciesForDropdown(): Collection
```

---

## Price Custom Module

### Overview
Manages custom pricing for specific clients and services.

### Database Schema
```sql
price_customs:
- id (bigint, primary key)
- module_id (bigint, foreign key to services)
- client_id (bigint, foreign key to clients)
- price_custom (decimal)
- is_active (boolean: true=active, false=inactive)
- currency_id (bigint, foreign key to currencies)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /price-customs | index | List all price customs |
| GET | /price-customs/create | create | Show create form |
| POST | /price-customs | store | Store new price custom |
| GET | /price-customs/{priceCustom} | show | Show price custom details |
| GET | /price-customs/{priceCustom}/edit | edit | Show edit form |
| PUT/PATCH | /price-customs/{priceCustom} | update | Update price custom |
| DELETE | /price-customs/{priceCustom} | destroy | Soft delete price custom |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/price-customs | Get all price customs |
| POST | /api/price-customs | Create price custom |
| GET | /api/price-customs/{id} | Get price custom by ID |
| PUT | /api/price-customs/{id} | Update price custom |
| DELETE | /api/price-customs/{id} | Soft delete price custom |

### Features
- **Custom Pricing**: Client-specific pricing overrides
- **Service-Client Combination**: Unique pricing per service-client pair
- **Multi-currency**: Different prices per currency
- **Soft Delete**: Price customs can be deactivated/reactivated
- **Priority**: Custom pricing overrides master pricing

### Service Methods
```php
// PriceCustomService
public function createPriceCustom(array $data): PriceCustom
public function updatePriceCustom(PriceCustom $priceCustom, array $data): bool
public function deletePriceCustom(PriceCustom $priceCustom): bool
public function restorePriceCustom(PriceCustom $priceCustom): bool
public function getServicesForDropdown(): Collection
public function getClientsForDropdown(): Collection
public function getCurrenciesForDropdown(): Collection
```

---

## Balance Management Module

### Overview
Manages client balances and balance top-up transactions.

### Database Schema
```sql
balances:
- id (bigint, primary key)
- client_id (bigint, foreign key to clients)
- balance (decimal)
- quota (decimal)
- created_at (timestamp)
- updated_at (timestamp)

balance_topups:
- id (bigint, primary key)
- client_id (bigint, foreign key to clients)
- amount (decimal)
- payment_method (varchar)
- status (varchar: approved, pending, cancelled, rejected)
- reference_number (varchar, nullable)
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /balance-topups | index | List all balance topups |
| GET | /balance-topups/create | create | Show create form |
| POST | /balance-topups | store | Store new balance topup |
| GET | /balance-topups/{balanceTopup} | show | Show balance topup details |
| GET | /balance-topups/{balanceTopup}/edit | edit | Show edit form |
| PUT/PATCH | /balance-topups/{balanceTopup} | update | Update balance topup |
| DELETE | /balance-topups/{balanceTopup} | destroy | Delete balance topup |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/balance-topups | Get all balance topups |
| POST | /api/balance-topups | Create balance topup |
| GET | /api/balance-topups/{id} | Get balance topup by ID |
| PUT | /api/balance-topups/{id} | Update balance topup |
| DELETE | /api/balance-topups/{id} | Delete balance topup |

### Features
- **Balance Tracking**: Real-time balance monitoring
- **Top-up Management**: Balance top-up transactions
- **Payment Methods**: Multiple payment options
- **Status Tracking**: Transaction status management
- **Reference Numbers**: Transaction tracking
- **Auto Balance Creation**: Balance created on client creation

### Service Methods
```php
// BalanceTopupService
public function createBalanceTopup(array $data): BalanceTopup
public function updateBalanceTopup(BalanceTopup $balanceTopup, array $data): bool
public function deleteBalanceTopup(BalanceTopup $balanceTopup): bool
public function getClientsForDropdown(): Collection
public function getPaymentMethods(): array
public function getStatusOptions(): array
```

---

## Transaction History Module

### Overview
Manages transaction logs and history for monitoring and reporting.

### Database Schema
```sql
histories:
- id (bigint, primary key)
- client_id (bigint, foreign key to clients)
- module_id (bigint, foreign key to services)
- trx_date (timestamp)
- price (decimal)
- status (varchar: OK, FAIL, INVALID_REQUEST)
- charge (varchar)
- created_at (timestamp)
- updated_at (timestamp)
```

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /histories | index | List all transaction histories |
| GET | /histories/{history} | show | Show history details |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/histories | Get all transaction histories |
| GET | /api/histories/{id} | Get history by ID |
| GET | /api/histories/client/{clientId} | Get histories by client |
| GET | /api/histories/service/{serviceId} | Get histories by service |

### Features
- **Transaction Logging**: Complete transaction records
- **Status Tracking**: Success, failure, and error tracking
- **Client Integration**: Linked to specific clients
- **Service Integration**: Linked to specific services
- **Date Filtering**: Transaction date range filtering
- **Reporting Data**: Source data for reports

### Service Methods
```php
// HistoryService
public function getHistoriesByClient(int $clientId, array $filters = []): Collection
public function getHistoriesByService(int $serviceId, array $filters = []): Collection
public function getHistoriesByDateRange(Carbon $startDate, Carbon $endDate): Collection
public function getTransactionStats(array $filters = []): array
```

---

## Reports Module

### Overview
Generates comprehensive reports for daily and monthly analysis.

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /reports/daily | daily | Show daily reports |
| GET | /reports/monthly | monthly | Show monthly reports |
| POST | /reports/daily/export | exportDaily | Export daily reports |
| POST | /reports/monthly/export | exportMonthly | Export monthly reports |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/reports/daily | Get daily report data |
| GET | /api/reports/monthly | Get monthly report data |
| POST | /api/reports/daily/export | Export daily reports |
| POST | /api/reports/monthly/export | Export monthly reports |

### Features
- **Daily Reports**: Comprehensive daily transaction analysis
- **Monthly Reports**: Monthly trends and statistics
- **Export Options**: Excel and PDF export
- **Filtering**: Date range, client, and service filters
- **Statistics**: Revenue, transaction counts, success rates
- **Charts**: Visual representation of data

### Report Data Includes
- Total transactions and revenue
- Success rates and failure analysis
- Top clients and services
- Hourly and daily trends
- Client type breakdown
- Service usage statistics

### Service Methods
```php
// ReportsService
public function getDailyReportData(Request $request): array
public function getMonthlyReportData(Request $request): array
public function getTransactionTypes(array $filters = []): Collection
public function getClientTypes(array $filters = []): Collection
public function getTopClients(array $filters = [], int $limit = 10): Collection
public function getTopServices(array $filters = [], int $limit = 10): Collection
```

---

## Dashboard Module

### Overview
Provides real-time dashboard with analytics and monitoring.

### Web Routes
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /dashboard | index | Show dashboard |
| GET | /dashboard/stats | getContextStats | Get context statistics |
| GET | /dashboard/recent | getRecentContext | Get recent context data |
| GET | /dashboard/relationships | getRelationships | Get relationship data |

### API Endpoints
| Method | URI | Description |
|--------|-----|-------------|
| GET | /api/dashboard | Get dashboard data |
| GET | /api/dashboard/stats | Get statistics |
| GET | /api/dashboard/recent | Get recent data |
| GET | /api/dashboard/charts | Get chart data |

### Features
- **Real-time Analytics**: Live data updates
- **7-Day Charts**: Transaction trends and revenue
- **Context Tables**: Recent users, services, clients
- **Statistics Cards**: Key metrics and KPIs
- **Interactive Charts**: Chart.js integration
- **Responsive Design**: Mobile-friendly interface

### Dashboard Components
- **Welcome Banner**: User greeting and current time
- **Statistics Overview**: Total counts and metrics
- **Context Tables**: Recent activity tables
- **7-Day Analytics**: Transaction trends chart
- **Top Services & Clients**: Performance rankings
- **Quick Actions**: Common task shortcuts

### Service Methods
```php
// DashboardController
public function index(): View
public function getContextStats(): JsonResponse
public function getRecentContext(): JsonResponse
public function getRelationships(): JsonResponse
private function getCoreStatistics(): array
private function getContextTablesData(): array
private function getRelationshipData(): array
private function getHistoriesChartData(): array
```

---

## Common Features Across All Modules

### Soft Delete Implementation
All modules support soft delete functionality:
- **Users**: `is_active` (integer)
- **Clients**: `is_active` (integer)
- **Services**: `is_active` (integer)
- **Currencies**: `is_active` (boolean)
- **Price Masters**: `is_active` (boolean)
- **Price Customs**: `is_active` (boolean)

### Search & Filtering
Universal search and filtering capabilities:
- **Text Search**: Name, email, description fields
- **Status Filter**: Active/Inactive records
- **Date Range**: Created/updated date filtering
- **Dropdown Filters**: Role, type, status selections
- **Pagination**: Configurable page sizes

### Export Functionality
Export capabilities for reports:
- **Excel Export**: .xlsx format with formatting
- **PDF Export**: Professional PDF reports
- **Data Filtering**: Export filtered data
- **Custom Headers**: Branded export headers

### Validation & Security
Comprehensive validation and security:
- **Form Validation**: Request classes for each module
- **CSRF Protection**: Cross-site request forgery protection
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Input sanitization
- **Role-based Access**: Permission-based access control

### Performance Optimization
Performance enhancements:
- **Redis Caching**: Client data caching
- **Query Optimization**: Eager loading and indexing
- **Pagination**: Large dataset handling
- **Lazy Loading**: On-demand data loading
- **Connection Pooling**: Database connection optimization

---

## API Response Format

### Success Response
```json
{
  "success": true,
  "data": {
    // Response data
  },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "error": "Error message",
  "errors": {
    // Validation errors
  }
}
```

### Pagination Response
```json
{
  "success": true,
  "data": {
    "data": [
      // Paginated items
    ],
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

## Authentication

### Web Authentication
- **Session-based**: Laravel session authentication
- **Remember Me**: Persistent login option
- **Password Reset**: Email-based password reset
- **Login Throttling**: Brute force protection

### API Authentication
- **Token-based**: Laravel Sanctum tokens
- **Client Credentials**: AK/SK authentication
- **Rate Limiting**: API request limiting
- **CORS Support**: Cross-origin resource sharing

---

This documentation provides a comprehensive overview of all modules in the Gateway Dashboard application, including their database schemas, routes, features, and service methods.
