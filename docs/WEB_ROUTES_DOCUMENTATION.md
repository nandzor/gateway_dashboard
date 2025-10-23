# Web Routes Documentation

## Overview
This document provides comprehensive documentation for all web routes in the Gateway Dashboard application, including their purposes, parameters, and associated views.

## Table of Contents
1. [Authentication Routes](#authentication-routes)
2. [Dashboard Routes](#dashboard-routes)
3. [User Management Routes](#user-management-routes)
4. [Client Management Routes](#client-management-routes)
5. [Service Management Routes](#service-management-routes)
6. [Currency Management Routes](#currency-management-routes)
7. [Price Master Routes](#price-master-routes)
8. [Price Custom Routes](#price-custom-routes)
9. [Balance Management Routes](#balance-management-routes)
10. [Transaction History Routes](#transaction-history-routes)
11. [Reports Routes](#reports-routes)
12. [Client Service Assignment Routes](#client-service-assignment-routes)

---

## Authentication Routes

### Login
```php
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
```

**Purpose**: User authentication
- **GET /login**: Display login form
- **POST /login**: Process login credentials

**View**: `auth.login`
**Middleware**: `guest`

### Logout
```php
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

**Purpose**: User logout
- **POST /logout**: Process logout request

**Middleware**: `auth`

### Password Reset
```php
Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
```

**Purpose**: Password reset functionality
- **GET /password/reset**: Display password reset request form
- **POST /password/email**: Send reset link email
- **GET /password/reset/{token}**: Display password reset form
- **POST /password/reset**: Process password reset

**Views**: `auth.passwords.email`, `auth.passwords.reset`
**Middleware**: `guest`

---

## Dashboard Routes

### Main Dashboard
```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

**Purpose**: Main dashboard page
- **GET /dashboard**: Display dashboard with analytics and context tables

**View**: `dashboard.index`
**Middleware**: `auth`

### Dashboard Data Endpoints
```php
Route::get('/dashboard/stats', [DashboardController::class, 'getContextStats'])->name('dashboard.stats');
Route::get('/dashboard/recent', [DashboardController::class, 'getRecentContext'])->name('dashboard.recent');
Route::get('/dashboard/relationships', [DashboardController::class, 'getRelationships'])->name('dashboard.relationships');
```

**Purpose**: AJAX endpoints for dashboard data
- **GET /dashboard/stats**: Get context statistics
- **GET /dashboard/recent**: Get recent context data
- **GET /dashboard/relationships**: Get relationship data

**Response**: JSON
**Middleware**: `auth`

---

## User Management Routes

### User Index
```php
Route::get('/users', [UserController::class, 'index'])->name('users.index');
```

**Purpose**: List all users with search and filtering
- **GET /users**: Display users list with pagination

**View**: `users.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by name, email, or username
- `role` (string): Filter by role (admin, operator, viewer)
- `status` (string): Filter by status (active, inactive)
- `per_page` (integer): Number of items per page
- `show_inactive` (boolean): Show inactive users

### User Create
```php
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
```

**Purpose**: Create new user
- **GET /users/create**: Display create user form
- **POST /users**: Process user creation

**Views**: `users.create`
**Middleware**: `auth`

### User Show
```php
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
```

**Purpose**: Display user details
- **GET /users/{user}`: Show user information

**View**: `users.show`
**Middleware**: `auth`

### User Edit
```php
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
```

**Purpose**: Edit existing user
- **GET /users/{user}/edit**: Display edit user form
- **PUT /users/{user}`: Process user update

**Views**: `users.edit`
**Middleware**: `auth`

### User Delete
```php
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
```

**Purpose**: Soft delete user
- **DELETE /users/{user}`: Deactivate user

**Middleware**: `auth`

---

## Client Management Routes

### Client Index
```php
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
```

**Purpose**: List all clients with search and filtering
- **GET /clients`: Display clients list with pagination

**View**: `clients.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by client name, contact, or address
- `type` (integer): Filter by type (1=prepaid, 2=postpaid)
- `status` (string): Filter by status (active, inactive)
- `staging` (boolean): Filter by staging status
- `per_page` (integer): Number of items per page

### Client Create
```php
Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
```

**Purpose**: Create new client
- **GET /clients/create**: Display create client form
- **POST /clients**: Process client creation

**Views**: `clients.create`
**Middleware**: `auth`

### Client Show
```php
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
```

**Purpose**: Display client details with balance topup history
- **GET /clients/{client}`: Show client information and related data

**View**: `clients.show`
**Middleware**: `auth`

### Client Edit
```php
Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
```

**Purpose**: Edit existing client
- **GET /clients/{client}/edit`: Display edit client form
- **PUT /clients/{client}`: Process client update

**Views**: `clients.edit`
**Middleware**: `auth`

### Client Delete
```php
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
```

**Purpose**: Soft delete client
- **DELETE /clients/{client}`: Deactivate client

**Middleware**: `auth`

---

## Service Management Routes

### Service Index
```php
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
```

**Purpose**: List all services with search and filtering
- **GET /services`: Display services list with pagination

**View**: `services.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by service name
- `type` (integer): Filter by type (1=internal, 2=external)
- `status` (string): Filter by status (active, inactive)
- `per_page` (integer): Number of items per page

### Service Create
```php
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
```

**Purpose**: Create new service
- **GET /services/create`: Display create service form
- **POST /services`: Process service creation

**Views**: `services.create`
**Middleware**: `auth`

### Service Show
```php
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
```

**Purpose**: Display service details
- **GET /services/{service}`: Show service information

**View**: `services.show`
**Middleware**: `auth`

### Service Edit
```php
Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
```

**Purpose**: Edit existing service
- **GET /services/{service}/edit`: Display edit service form
- **PUT /services/{service}`: Process service update

**Views**: `services.edit`
**Middleware**: `auth`

### Service Delete
```php
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
```

**Purpose**: Soft delete service
- **DELETE /services/{service}`: Deactivate service

**Middleware**: `auth`

---

## Currency Management Routes

### Currency Index
```php
Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
```

**Purpose**: List all currencies with search and filtering
- **GET /currencies`: Display currencies list with pagination

**View**: `currencies.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by name or code
- `status` (string): Filter by status (active, inactive)
- `per_page` (integer): Number of items per page

### Currency Create
```php
Route::get('/currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
Route::post('/currencies', [CurrencyController::class, 'store'])->name('currencies.store');
```

**Purpose**: Create new currency
- **GET /currencies/create`: Display create currency form
- **POST /currencies`: Process currency creation

**Views**: `currencies.create`
**Middleware**: `auth`

### Currency Show
```php
Route::get('/currencies/{currency}', [CurrencyController::class, 'show'])->name('currencies.show');
```

**Purpose**: Display currency details
- **GET /currencies/{currency}`: Show currency information

**View**: `currencies.show`
**Middleware**: `auth`

### Currency Edit
```php
Route::get('/currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
Route::put('/currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
```

**Purpose**: Edit existing currency
- **GET /currencies/{currency}/edit`: Display edit currency form
- **PUT /currencies/{currency}`: Process currency update

**Views**: `currencies.edit`
**Middleware**: `auth`

### Currency Delete
```php
Route::delete('/currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
```

**Purpose**: Soft delete currency
- **DELETE /currencies/{currency}`: Deactivate currency

**Middleware**: `auth`

---

## Price Master Routes

### Price Master Index
```php
Route::get('/price-masters', [PriceMasterController::class, 'index'])->name('price-masters.index');
```

**Purpose**: List all price masters with search and filtering
- **GET /price-masters`: Display price masters list with pagination

**View**: `price-masters.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by service name or note
- `service_id` (integer): Filter by service ID
- `currency_id` (integer): Filter by currency ID
- `status` (string): Filter by status (active, inactive)
- `per_page` (integer): Number of items per page

### Price Master Create
```php
Route::get('/price-masters/create', [PriceMasterController::class, 'create'])->name('price-masters.create');
Route::post('/price-masters', [PriceMasterController::class, 'store'])->name('price-masters.store');
```

**Purpose**: Create new price master
- **GET /price-masters/create`: Display create price master form
- **POST /price-masters`: Process price master creation

**Views**: `price-masters.create`
**Middleware**: `auth`

### Price Master Show
```php
Route::get('/price-masters/{priceMaster}', [PriceMasterController::class, 'show'])->name('price-masters.show');
```

**Purpose**: Display price master details
- **GET /price-masters/{priceMaster}`: Show price master information

**View**: `price-masters.show`
**Middleware**: `auth`

### Price Master Edit
```php
Route::get('/price-masters/{priceMaster}/edit', [PriceMasterController::class, 'edit'])->name('price-masters.edit');
Route::put('/price-masters/{priceMaster}', [PriceMasterController::class, 'update'])->name('price-masters.update');
```

**Purpose**: Edit existing price master
- **GET /price-masters/{priceMaster}/edit`: Display edit price master form
- **PUT /price-masters/{priceMaster}`: Process price master update

**Views**: `price-masters.edit`
**Middleware**: `auth`

### Price Master Delete
```php
Route::delete('/price-masters/{priceMaster}', [PriceMasterController::class, 'destroy'])->name('price-masters.destroy');
```

**Purpose**: Soft delete price master
- **DELETE /price-masters/{priceMaster}`: Deactivate price master

**Middleware**: `auth`

---

## Price Custom Routes

### Price Custom Index
```php
Route::get('/price-customs', [PriceCustomController::class, 'index'])->name('price-customs.index');
```

**Purpose**: List all price customs with search and filtering
- **GET /price-customs`: Display price customs list with pagination

**View**: `price-customs.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by service name or client name
- `service_id` (integer): Filter by service ID
- `client_id` (integer): Filter by client ID
- `currency_id` (integer): Filter by currency ID
- `status` (string): Filter by status (active, inactive)
- `per_page` (integer): Number of items per page

### Price Custom Create
```php
Route::get('/price-customs/create', [PriceCustomController::class, 'create'])->name('price-customs.create');
Route::post('/price-customs', [PriceCustomController::class, 'store'])->name('price-customs.store');
```

**Purpose**: Create new price custom
- **GET /price-customs/create`: Display create price custom form
- **POST /price-customs`: Process price custom creation

**Views**: `price-customs.create`
**Middleware**: `auth`

### Price Custom Show
```php
Route::get('/price-customs/{priceCustom}', [PriceCustomController::class, 'show'])->name('price-customs.show');
```

**Purpose**: Display price custom details
- **GET /price-customs/{priceCustom}`: Show price custom information

**View**: `price-customs.show`
**Middleware**: `auth`

### Price Custom Edit
```php
Route::get('/price-customs/{priceCustom}/edit', [PriceCustomController::class, 'edit'])->name('price-customs.edit');
Route::put('/price-customs/{priceCustom}', [PriceCustomController::class, 'update'])->name('price-customs.update');
```

**Purpose**: Edit existing price custom
- **GET /price-customs/{priceCustom}/edit`: Display edit price custom form
- **PUT /price-customs/{priceCustom}`: Process price custom update

**Views**: `price-customs.edit`
**Middleware**: `auth`

### Price Custom Delete
```php
Route::delete('/price-customs/{priceCustom}', [PriceCustomController::class, 'destroy'])->name('price-customs.destroy');
```

**Purpose**: Soft delete price custom
- **DELETE /price-customs/{priceCustom}`: Deactivate price custom

**Middleware**: `auth`

---

## Balance Management Routes

### Balance Topup Index
```php
Route::get('/balance-topups', [BalanceTopupController::class, 'index'])->name('balance-topups.index');
```

**Purpose**: List all balance topups with search and filtering
- **GET /balance-topups`: Display balance topups list with pagination

**View**: `balance-topups.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by client name or reference number
- `client_id` (integer): Filter by client ID
- `status` (string): Filter by status (approved, pending, cancelled, rejected)
- `payment_method` (string): Filter by payment method
- `per_page` (integer): Number of items per page

### Balance Topup Create
```php
Route::get('/balance-topups/create', [BalanceTopupController::class, 'create'])->name('balance-topups.create');
Route::post('/balance-topups', [BalanceTopupController::class, 'store'])->name('balance-topups.store');
```

**Purpose**: Create new balance topup
- **GET /balance-topups/create`: Display create balance topup form
- **POST /balance-topups`: Process balance topup creation

**Views**: `balance-topups.create`
**Middleware**: `auth`

### Balance Topup Show
```php
Route::get('/balance-topups/{balanceTopup}', [BalanceTopupController::class, 'show'])->name('balance-topups.show');
```

**Purpose**: Display balance topup details
- **GET /balance-topups/{balanceTopup}`: Show balance topup information

**View**: `balance-topups.show`
**Middleware**: `auth`

### Balance Topup Edit
```php
Route::get('/balance-topups/{balanceTopup}/edit', [BalanceTopupController::class, 'edit'])->name('balance-topups.edit');
Route::put('/balance-topups/{balanceTopup}', [BalanceTopupController::class, 'update'])->name('balance-topups.update');
```

**Purpose**: Edit existing balance topup
- **GET /balance-topups/{balanceTopup}/edit`: Display edit balance topup form
- **PUT /balance-topups/{balanceTopup}`: Process balance topup update

**Views**: `balance-topups.edit`
**Middleware**: `auth`

### Balance Topup Delete
```php
Route::delete('/balance-topups/{balanceTopup}', [BalanceTopupController::class, 'destroy'])->name('balance-topups.destroy');
```

**Purpose**: Delete balance topup
- **DELETE /balance-topups/{balanceTopup}`: Delete balance topup

**Middleware**: `auth`

---

## Transaction History Routes

### History Index
```php
Route::get('/histories', [HistoryController::class, 'index'])->name('histories.index');
```

**Purpose**: List all transaction histories with search and filtering
- **GET /histories`: Display histories list with pagination

**View**: `histories.index`
**Middleware**: `auth`

**Query Parameters**:
- `search` (string): Search by client name or service name
- `client_id` (integer): Filter by client ID
- `service_id` (integer): Filter by service ID
- `status` (string): Filter by status (OK, FAIL, INVALID_REQUEST)
- `date_from` (date): Filter from date
- `date_to` (date): Filter to date
- `per_page` (integer): Number of items per page

### History Show
```php
Route::get('/histories/{history}', [HistoryController::class, 'show'])->name('histories.show');
```

**Purpose**: Display transaction history details
- **GET /histories/{history}`: Show history information

**View**: `histories.show`
**Middleware**: `auth`

---

## Reports Routes

### Daily Reports
```php
Route::get('/reports/daily', [ReportsController::class, 'daily'])->name('reports.daily');
```

**Purpose**: Display daily reports with analytics
- **GET /reports/daily`: Show daily report page

**View**: `reports.daily`
**Middleware**: `auth`

**Query Parameters**:
- `date` (date): Report date (default: today)
- `client_id` (integer): Filter by client ID
- `service_id` (integer): Filter by service ID

### Monthly Reports
```php
Route::get('/reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
```

**Purpose**: Display monthly reports with analytics
- **GET /reports/monthly`: Show monthly report page

**View**: `reports.monthly`
**Middleware**: `auth`

**Query Parameters**:
- `month` (string): Report month in YYYY-MM format (default: current month)
- `client_id` (integer): Filter by client ID
- `service_id` (integer): Filter by service ID

### Export Daily Reports
```php
Route::post('/reports/daily/export', [ReportsController::class, 'exportDaily'])->name('reports.daily.export');
```

**Purpose**: Export daily reports
- **POST /reports/daily/export`: Export daily report data

**Request Body**:
- `format` (string): Export format (excel, pdf)
- `date` (date): Report date
- `client_id` (integer): Filter by client ID
- `service_id` (integer): Filter by service ID

**Response**: File download (Excel or PDF)
**Middleware**: `auth`

### Export Monthly Reports
```php
Route::post('/reports/monthly/export', [ReportsController::class, 'exportMonthly'])->name('reports.monthly.export');
```

**Purpose**: Export monthly reports
- **POST /reports/monthly/export`: Export monthly report data

**Request Body**:
- `format` (string): Export format (excel, pdf)
- `month` (string): Report month in YYYY-MM format
- `client_id` (integer): Filter by client ID
- `service_id` (integer): Filter by service ID

**Response**: File download (Excel or PDF)
**Middleware**: `auth`

---

## Client Service Assignment Routes

### Service Assignment Index
```php
Route::get('/client-service-assignments', [ClientServiceAssignmentController::class, 'index'])->name('client-service-assignments.index');
```

**Purpose**: List all client service assignments
- **GET /client-service-assignments`: Display assignments list with pagination

**View**: `client-service-assignments.index`
**Middleware**: `auth`

### Service Assignment Create
```php
Route::get('/client-service-assignments/create', [ClientServiceAssignmentController::class, 'create'])->name('client-service-assignments.create');
Route::post('/client-service-assignments', [ClientServiceAssignmentController::class, 'store'])->name('client-service-assignments.store');
```

**Purpose**: Create new client service assignment
- **GET /client-service-assignments/create`: Display create assignment form
- **POST /client-service-assignments`: Process assignment creation

**Views**: `client-service-assignments.create`
**Middleware**: `auth`

### Service Assignment Show
```php
Route::get('/client-service-assignments/{assignment}', [ClientServiceAssignmentController::class, 'show'])->name('client-service-assignments.show');
```

**Purpose**: Display service assignment details
- **GET /client-service-assignments/{assignment}`: Show assignment information

**View**: `client-service-assignments.show`
**Middleware**: `auth`

### Service Assignment Edit
```php
Route::get('/client-service-assignments/{assignment}/edit', [ClientServiceAssignmentController::class, 'edit'])->name('client-service-assignments.edit');
Route::put('/client-service-assignments/{assignment}', [ClientServiceAssignmentController::class, 'update'])->name('client-service-assignments.update');
```

**Purpose**: Edit existing service assignment
- **GET /client-service-assignments/{assignment}/edit`: Display edit assignment form
- **PUT /client-service-assignments/{assignment}`: Process assignment update

**Views**: `client-service-assignments.edit`
**Middleware**: `auth`

### Service Assignment Delete
```php
Route::delete('/client-service-assignments/{assignment}', [ClientServiceAssignmentController::class, 'destroy'])->name('client-service-assignments.destroy');
```

**Purpose**: Delete service assignment
- **DELETE /client-service-assignments/{assignment}`: Delete assignment

**Middleware**: `auth`

---

## Route Groups and Middleware

### Authentication Middleware
```php
Route::middleware(['auth'])->group(function () {
    // All authenticated routes
});
```

**Purpose**: Protect routes that require authentication
**Applied to**: All main application routes

### Guest Middleware
```php
Route::middleware(['guest'])->group(function () {
    // Login and registration routes
});
```

**Purpose**: Redirect authenticated users away from login/register pages
**Applied to**: Authentication routes

### Role-based Middleware
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes
});
```

**Purpose**: Restrict access based on user roles
**Roles**: admin, operator, viewer

---

## View Components

### Layout Structure
- **Main Layout**: `layouts.app`
- **Sidebar**: `components.sidebar`
- **Header**: `components.header`
- **Footer**: `components.footer`

### Common Components
- **x-card**: Card layout component
- **x-input**: Form input component
- **x-select**: Dropdown select component
- **x-badge**: Status badge component
- **x-button**: Button component
- **x-pagination**: Pagination component
- **x-sidebar-link**: Navigation link component
- **x-sidebar-icon**: Navigation icon component
- **x-sidebar-section**: Navigation section component
- **x-stat-card**: Statistics card component
- **x-dropdown**: Dropdown component
- **x-dropdown-link**: Dropdown link component
- **x-empty-state**: Empty state component
- **x-confirm-modal**: Confirmation modal component

### Page Structure
Each page typically includes:
1. **Page Header**: Title and breadcrumbs
2. **Filters**: Search and filter controls
3. **Action Buttons**: Create, export, etc.
4. **Data Table**: Paginated data display
5. **Pagination**: Navigation controls

---

## Form Handling

### Create Forms
- **Method**: GET
- **Action**: POST to store route
- **Validation**: Request classes
- **Success**: Redirect to index with success message
- **Error**: Redirect back with validation errors

### Edit Forms
- **Method**: GET
- **Action**: PUT/PATCH to update route
- **Validation**: Request classes
- **Success**: Redirect to show route with success message
- **Error**: Redirect back with validation errors

### Delete Actions
- **Method**: DELETE
- **Confirmation**: JavaScript confirmation modal
- **Success**: Redirect to index with success message
- **Error**: Redirect back with error message

---

## Search and Filtering

### Universal Search
All index pages support:
- **Text Search**: Search across relevant fields
- **Status Filter**: Active/Inactive records
- **Date Range**: Created/updated date filtering
- **Dropdown Filters**: Role, type, status selections
- **Pagination**: Configurable page sizes

### Search Implementation
- **Frontend**: Alpine.js for real-time search
- **Backend**: Service layer handles search logic
- **Debounce**: 300ms delay for search input
- **Loading**: Loading indicators during search

---

## Error Handling

### Common Error Scenarios
- **404 Not Found**: Resource not found
- **403 Forbidden**: Insufficient permissions
- **422 Validation Error**: Form validation failed
- **500 Internal Server Error**: Server error

### Error Display
- **Flash Messages**: Success/error messages
- **Validation Errors**: Field-specific error messages
- **Error Pages**: Custom error pages for 404, 500, etc.

---

## Security Features

### CSRF Protection
- **Token**: CSRF token in all forms
- **Verification**: Server-side token verification
- **Exception**: API routes (if using token authentication)

### Input Validation
- **Request Classes**: Dedicated validation classes
- **Sanitization**: Input sanitization
- **XSS Protection**: Output escaping

### Authorization
- **Role-based Access**: Different access levels
- **Resource Ownership**: Users can only access their own resources
- **Admin Protection**: Admin-only actions

---

This comprehensive web routes documentation covers all routes, their purposes, parameters, views, and associated functionality in the Gateway Dashboard application.
