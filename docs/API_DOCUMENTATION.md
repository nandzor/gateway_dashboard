# API Documentation

## Overview
This document provides comprehensive API documentation for the Gateway Dashboard application, including all endpoints, request/response formats, and authentication methods.

## Base URL
```
Web: http://localhost:8000
API: http://localhost:8000/api
```

## Authentication

### Web Authentication
- **Method**: Session-based authentication
- **Login**: `POST /login`
- **Logout**: `POST /logout`
- **Register**: `POST /register` (if enabled)

### API Authentication
- **Method**: Laravel Sanctum tokens
- **Header**: `Authorization: Bearer {token}`
- **Client Credentials**: API Key (AK) and Secret Key (SK)

## Response Format

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
    "field": ["Validation error message"]
  }
}
```

### Pagination Response
```json
{
  "success": true,
  "data": {
    "data": [
      // Array of items
    ],
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150,
    "from": 1,
    "to": 15
  }
}
```

---

## User Management API

### Get All Users
```http
GET /api/users
```

**Query Parameters:**
- `search` (string, optional): Search by name, email, or username
- `role` (string, optional): Filter by role (admin, operator, viewer)
- `status` (string, optional): Filter by status (active, inactive)
- `per_page` (integer, optional): Number of items per page (default: 10)
- `page` (integer, optional): Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "username": "admin",
        "name": "Administrator",
        "email": "admin@example.com",
        "role": "admin",
        "is_active": 1,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get User by ID
```http
GET /api/users/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin",
    "name": "Administrator",
    "email": "admin@example.com",
    "role": "admin",
    "is_active": 1,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create User
```http
POST /api/users
```

**Request Body:**
```json
{
  "username": "newuser",
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "operator",
  "is_active": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "username": "newuser",
    "name": "New User",
    "email": "newuser@example.com",
    "role": "operator",
    "is_active": 1,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "User created successfully"
}
```

### Update User
```http
PUT /api/users/{id}
```

**Request Body:**
```json
{
  "name": "Updated User",
  "email": "updated@example.com",
  "role": "admin",
  "is_active": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin",
    "name": "Updated User",
    "email": "updated@example.com",
    "role": "admin",
    "is_active": 1,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "User updated successfully"
}
```

### Delete User (Soft Delete)
```http
DELETE /api/users/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "User deactivated successfully"
}
```

---

## Client Management API

### Get All Clients
```http
GET /api/clients
```

**Query Parameters:**
- `search` (string, optional): Search by client name, contact, or address
- `type` (integer, optional): Filter by type (1=prepaid, 2=postpaid)
- `status` (string, optional): Filter by status (active, inactive)
- `staging` (boolean, optional): Filter by staging status
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "client_name": "Test Client",
        "address": "123 Main St",
        "contact": "client@example.com",
        "type": 1,
        "type_name": "Prepaid",
        "ak": "ak_123456789",
        "sk": "sk_987654321",
        "avkey_iv": "iv_123456",
        "avkey_pass": "pass_123456",
        "service_module": 1,
        "service_module_name": "Service 1",
        "is_active": 1,
        "is_staging": 0,
        "service_allow": [1, 2, 3],
        "service_allow_name": ["Service 1", "Service 2", "Service 3"],
        "white_list": ["*"],
        "module_40": {
          "Iv": "",
          "Pass": "",
          "Bearer": ""
        },
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Client by ID
```http
GET /api/clients/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "client_name": "Test Client",
    "address": "123 Main St",
    "contact": "client@example.com",
    "type": 1,
    "type_name": "Prepaid",
    "ak": "ak_123456789",
    "sk": "sk_987654321",
    "avkey_iv": "iv_123456",
    "avkey_pass": "pass_123456",
    "service_module": 1,
    "service_module_name": "Service 1",
    "is_active": 1,
    "is_staging": 0,
    "service_allow": [1, 2, 3],
    "service_allow_name": ["Service 1", "Service 2", "Service 3"],
    "white_list": ["*"],
    "module_40": {
      "Iv": "",
      "Pass": "",
      "Bearer": ""
    },
    "balances": [
      {
        "id": 1,
        "balance": 1000.00,
        "quota": 500.00,
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "balance_topups": [
      {
        "id": 1,
        "amount": 1000.00,
        "payment_method": "bank_transfer",
        "status": "approved",
        "reference_number": "REF123456",
        "notes": "Initial topup",
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Client
```http
POST /api/clients
```

**Request Body:**
```json
{
  "client_name": "New Client",
  "address": "456 Oak Ave",
  "contact": "newclient@example.com",
  "type": 1,
  "ak": "ak_new123456",
  "sk": "sk_new654321",
  "avkey_iv": "iv_new123",
  "avkey_pass": "pass_new123",
  "service_module": 1,
  "is_active": 1,
  "is_staging": 0,
  "service_allow": [1, 2],
  "white_list": ["192.168.1.1", "10.0.0.1"],
  "module_40": {
    "Iv": "module_iv",
    "Pass": "module_pass",
    "Bearer": "module_bearer"
  }
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "client_name": "New Client",
    "address": "456 Oak Ave",
    "contact": "newclient@example.com",
    "type": 1,
    "type_name": "Prepaid",
    "ak": "ak_new123456",
    "sk": "sk_new654321",
    "avkey_iv": "iv_new123",
    "avkey_pass": "pass_new123",
    "service_module": 1,
    "service_module_name": "Service 1",
    "is_active": 1,
    "is_staging": 0,
    "service_allow": [1, 2],
    "service_allow_name": ["Service 1", "Service 2"],
    "white_list": ["192.168.1.1", "10.0.0.1"],
    "module_40": {
      "Iv": "module_iv",
      "Pass": "module_pass",
      "Bearer": "module_bearer"
    },
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Client created successfully"
}
```

### Update Client
```http
PUT /api/clients/{id}
```

**Request Body:**
```json
{
  "client_name": "Updated Client",
  "address": "789 Pine St",
  "contact": "updated@example.com",
  "type": 2,
  "service_allow": [1, 2, 3, 4],
  "white_list": ["*"]
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "client_name": "Updated Client",
    "address": "789 Pine St",
    "contact": "updated@example.com",
    "type": 2,
    "type_name": "Postpaid",
    "service_allow": [1, 2, 3, 4],
    "service_allow_name": ["Service 1", "Service 2", "Service 3", "Service 4"],
    "white_list": ["*"],
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Client updated successfully"
}
```

### Delete Client (Soft Delete)
```http
DELETE /api/clients/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Client deactivated successfully"
}
```

---

## Service Management API

### Get All Services
```http
GET /api/services
```

**Query Parameters:**
- `search` (string, optional): Search by service name
- `type` (integer, optional): Filter by type (1=internal, 2=external)
- `status` (string, optional): Filter by status (active, inactive)
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "SMS Service",
        "type": 1,
        "type_name": "Internal",
        "is_active": 1,
        "is_alert_zero": 1,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Service by ID
```http
GET /api/services/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "SMS Service",
    "type": 1,
    "type_name": "Internal",
    "is_active": 1,
    "is_alert_zero": 1,
    "clients": [
      {
        "id": 1,
        "client_name": "Test Client",
        "pivot": {
          "client_id": 1,
          "service_id": 1,
          "created_at": "2024-01-01T00:00:00.000000Z",
          "updated_at": "2024-01-01T00:00:00.000000Z"
        }
      }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Service
```http
POST /api/services
```

**Request Body:**
```json
{
  "name": "Email Service",
  "type": 2,
  "is_active": 1,
  "is_alert_zero": 0
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "Email Service",
    "type": 2,
    "type_name": "External",
    "is_active": 1,
    "is_alert_zero": 0,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Service created successfully"
}
```

### Update Service
```http
PUT /api/services/{id}
```

**Request Body:**
```json
{
  "name": "Updated SMS Service",
  "type": 1,
  "is_alert_zero": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Updated SMS Service",
    "type": 1,
    "type_name": "Internal",
    "is_active": 1,
    "is_alert_zero": 1,
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Service updated successfully"
}
```

### Delete Service (Soft Delete)
```http
DELETE /api/services/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Service deactivated successfully"
}
```

---

## Currency Management API

### Get All Currencies
```http
GET /api/currencies
```

**Query Parameters:**
- `search` (string, optional): Search by name or code
- `status` (string, optional): Filter by status (active, inactive)
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Indonesian Rupiah",
        "code": "IDR",
        "symbol": "Rp",
        "is_active": true,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Currency by ID
```http
GET /api/currencies/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Indonesian Rupiah",
    "code": "IDR",
    "symbol": "Rp",
    "is_active": true,
    "price_masters": [
      {
        "id": 1,
        "module_id": 1,
        "price_default": 1000.000,
        "is_active": true,
        "note": "Default SMS price",
        "currency_id": 1,
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "price_customs": [
      {
        "id": 1,
        "module_id": 1,
        "client_id": 1,
        "price_custom": 800.000,
        "is_active": true,
        "currency_id": 1,
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Currency
```http
POST /api/currencies
```

**Request Body:**
```json
{
  "name": "US Dollar",
  "code": "USD",
  "symbol": "$",
  "is_active": true
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "US Dollar",
    "code": "USD",
    "symbol": "$",
    "is_active": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Currency created successfully"
}
```

### Update Currency
```http
PUT /api/currencies/{id}
```

**Request Body:**
```json
{
  "name": "Updated Indonesian Rupiah",
  "symbol": "Rp."
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Updated Indonesian Rupiah",
    "code": "IDR",
    "symbol": "Rp.",
    "is_active": true,
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Currency updated successfully"
}
```

### Delete Currency (Soft Delete)
```http
DELETE /api/currencies/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Currency deactivated successfully"
}
```

---

## Price Master API

### Get All Price Masters
```http
GET /api/price-masters
```

**Query Parameters:**
- `search` (string, optional): Search by service name or note
- `service_id` (integer, optional): Filter by service ID
- `currency_id` (integer, optional): Filter by currency ID
- `status` (string, optional): Filter by status (active, inactive)
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "module_id": 1,
        "service_name": "SMS Service",
        "price_default": 1000.000,
        "is_active": true,
        "note": "Default SMS price",
        "currency_id": 1,
        "currency_name": "Indonesian Rupiah",
        "formatted_price": "Rp 1.000",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Price Master by ID
```http
GET /api/price-masters/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "module_id": 1,
    "service": {
      "id": 1,
      "name": "SMS Service",
      "type": 1,
      "type_name": "Internal"
    },
    "price_default": 1000.000,
    "is_active": true,
    "note": "Default SMS price",
    "currency_id": 1,
    "currency": {
      "id": 1,
      "name": "Indonesian Rupiah",
      "code": "IDR",
      "symbol": "Rp"
    },
    "formatted_price": "Rp 1.000",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Price Master
```http
POST /api/price-masters
```

**Request Body:**
```json
{
  "module_id": 1,
  "price_default": 1500.000,
  "is_active": true,
  "note": "Premium SMS price",
  "currency_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "module_id": 1,
    "service_name": "SMS Service",
    "price_default": 1500.000,
    "is_active": true,
    "note": "Premium SMS price",
    "currency_id": 1,
    "currency_name": "Indonesian Rupiah",
    "formatted_price": "Rp 1.500",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Price master created successfully"
}
```

### Update Price Master
```http
PUT /api/price-masters/{id}
```

**Request Body:**
```json
{
  "price_default": 1200.000,
  "note": "Updated SMS price"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "module_id": 1,
    "service_name": "SMS Service",
    "price_default": 1200.000,
    "is_active": true,
    "note": "Updated SMS price",
    "currency_id": 1,
    "currency_name": "Indonesian Rupiah",
    "formatted_price": "Rp 1.200",
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Price master updated successfully"
}
```

### Delete Price Master (Soft Delete)
```http
DELETE /api/price-masters/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Price master deactivated successfully"
}
```

---

## Price Custom API

### Get All Price Customs
```http
GET /api/price-customs
```

**Query Parameters:**
- `search` (string, optional): Search by service name or client name
- `service_id` (integer, optional): Filter by service ID
- `client_id` (integer, optional): Filter by client ID
- `currency_id` (integer, optional): Filter by currency ID
- `status` (string, optional): Filter by status (active, inactive)
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "module_id": 1,
        "service_name": "SMS Service",
        "client_id": 1,
        "client_name": "Test Client",
        "price_custom": 800.000,
        "is_active": true,
        "currency_id": 1,
        "currency_name": "Indonesian Rupiah",
        "formatted_price": "Rp 800",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Price Custom by ID
```http
GET /api/price-customs/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "module_id": 1,
    "service": {
      "id": 1,
      "name": "SMS Service",
      "type": 1,
      "type_name": "Internal"
    },
    "client_id": 1,
    "client": {
      "id": 1,
      "client_name": "Test Client",
      "type": 1,
      "type_name": "Prepaid"
    },
    "price_custom": 800.000,
    "is_active": true,
    "currency_id": 1,
    "currency": {
      "id": 1,
      "name": "Indonesian Rupiah",
      "code": "IDR",
      "symbol": "Rp"
    },
    "formatted_price": "Rp 800",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Price Custom
```http
POST /api/price-customs
```

**Request Body:**
```json
{
  "module_id": 1,
  "client_id": 1,
  "price_custom": 750.000,
  "is_active": true,
  "currency_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "module_id": 1,
    "service_name": "SMS Service",
    "client_id": 1,
    "client_name": "Test Client",
    "price_custom": 750.000,
    "is_active": true,
    "currency_id": 1,
    "currency_name": "Indonesian Rupiah",
    "formatted_price": "Rp 750",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Price custom created successfully"
}
```

### Update Price Custom
```http
PUT /api/price-customs/{id}
```

**Request Body:**
```json
{
  "price_custom": 900.000
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "module_id": 1,
    "service_name": "SMS Service",
    "client_id": 1,
    "client_name": "Test Client",
    "price_custom": 900.000,
    "is_active": true,
    "currency_id": 1,
    "currency_name": "Indonesian Rupiah",
    "formatted_price": "Rp 900",
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Price custom updated successfully"
}
```

### Delete Price Custom (Soft Delete)
```http
DELETE /api/price-customs/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Price custom deactivated successfully"
}
```

---

## Balance Management API

### Get All Balance Topups
```http
GET /api/balance-topups
```

**Query Parameters:**
- `search` (string, optional): Search by client name or reference number
- `client_id` (integer, optional): Filter by client ID
- `status` (string, optional): Filter by status (approved, pending, cancelled, rejected)
- `payment_method` (string, optional): Filter by payment method
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "client_id": 1,
        "client_name": "Test Client",
        "amount": 1000.00,
        "payment_method": "bank_transfer",
        "status": "approved",
        "reference_number": "REF123456",
        "notes": "Initial topup",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Balance Topup by ID
```http
GET /api/balance-topups/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "client_id": 1,
    "client": {
      "id": 1,
      "client_name": "Test Client",
      "type": 1,
      "type_name": "Prepaid"
    },
    "amount": 1000.00,
    "payment_method": "bank_transfer",
    "status": "approved",
    "reference_number": "REF123456",
    "notes": "Initial topup",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Create Balance Topup
```http
POST /api/balance-topups
```

**Request Body:**
```json
{
  "client_id": 1,
  "amount": 5000.00,
  "payment_method": "credit_card",
  "status": "pending",
  "reference_number": "REF789012",
  "notes": "Monthly topup"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "client_id": 1,
    "client_name": "Test Client",
    "amount": 5000.00,
    "payment_method": "credit_card",
    "status": "pending",
    "reference_number": "REF789012",
    "notes": "Monthly topup",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Balance topup created successfully"
}
```

### Update Balance Topup
```http
PUT /api/balance-topups/{id}
```

**Request Body:**
```json
{
  "status": "approved",
  "notes": "Approved by admin"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "client_id": 1,
    "client_name": "Test Client",
    "amount": 5000.00,
    "payment_method": "credit_card",
    "status": "approved",
    "reference_number": "REF789012",
    "notes": "Approved by admin",
    "updated_at": "2024-01-01T12:00:00.000000Z"
  },
  "message": "Balance topup updated successfully"
}
```

### Delete Balance Topup
```http
DELETE /api/balance-topups/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Balance topup deleted successfully"
}
```

---

## Transaction History API

### Get All Transaction Histories
```http
GET /api/histories
```

**Query Parameters:**
- `search` (string, optional): Search by client name or service name
- `client_id` (integer, optional): Filter by client ID
- `service_id` (integer, optional): Filter by service ID
- `status` (string, optional): Filter by status (OK, FAIL, INVALID_REQUEST)
- `date_from` (date, optional): Filter from date
- `date_to` (date, optional): Filter to date
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "client_id": 1,
        "client_name": "Test Client",
        "module_id": 1,
        "service_name": "SMS Service",
        "trx_date": "2024-01-01T10:30:00.000000Z",
        "price": 1000.00,
        "status": "OK",
        "charge": "prepaid",
        "created_at": "2024-01-01T10:30:00.000000Z",
        "updated_at": "2024-01-01T10:30:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get History by ID
```http
GET /api/histories/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "client_id": 1,
    "client": {
      "id": 1,
      "client_name": "Test Client",
      "type": 1,
      "type_name": "Prepaid"
    },
    "module_id": 1,
    "service": {
      "id": 1,
      "name": "SMS Service",
      "type": 1,
      "type_name": "Internal"
    },
    "trx_date": "2024-01-01T10:30:00.000000Z",
    "price": 1000.00,
    "status": "OK",
    "charge": "prepaid",
    "created_at": "2024-01-01T10:30:00.000000Z",
    "updated_at": "2024-01-01T10:30:00.000000Z"
  }
}
```

### Get Histories by Client
```http
GET /api/histories/client/{clientId}
```

**Query Parameters:**
- `date_from` (date, optional): Filter from date
- `date_to` (date, optional): Filter to date
- `status` (string, optional): Filter by status
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "client_id": 1,
        "client_name": "Test Client",
        "module_id": 1,
        "service_name": "SMS Service",
        "trx_date": "2024-01-01T10:30:00.000000Z",
        "price": 1000.00,
        "status": "OK",
        "charge": "prepaid",
        "created_at": "2024-01-01T10:30:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

### Get Histories by Service
```http
GET /api/histories/service/{serviceId}
```

**Query Parameters:**
- `date_from` (date, optional): Filter from date
- `date_to` (date, optional): Filter to date
- `status` (string, optional): Filter by status
- `per_page` (integer, optional): Number of items per page

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "client_id": 1,
        "client_name": "Test Client",
        "module_id": 1,
        "service_name": "SMS Service",
        "trx_date": "2024-01-01T10:30:00.000000Z",
        "price": 1000.00,
        "status": "OK",
        "charge": "prepaid",
        "created_at": "2024-01-01T10:30:00.000000Z"
      }
    ],
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

---

## Reports API

### Get Daily Report Data
```http
GET /api/reports/daily
```

**Query Parameters:**
- `date` (date, optional): Report date (default: today)
- `client_id` (integer, optional): Filter by client ID
- `service_id` (integer, optional): Filter by service ID

**Response:**
```json
{
  "success": true,
  "data": {
    "summary": {
      "Total Transactions": 150,
      "Total Revenue": 150000.00,
      "Total Duration": 3600,
      "Unique Users": 25,
      "Unique Clients": 10,
      "Success Rate": "95.5%",
      "Avg Transaction Value": 1000.00,
      "Peak Hour": "14:00",
      "Busiest Service": "SMS Service",
      "Top Client": "Test Client"
    },
    "transaction_types": {
      "prepaid": {
        "count": 120,
        "revenue": 120000.00,
        "duration": 3000
      },
      "postpaid": {
        "count": 30,
        "revenue": 30000.00,
        "duration": 600
      }
    },
    "client_types": {
      "Prepaid": {
        "count": 120,
        "revenue": 120000.00,
        "duration": 3000
      },
      "Postpaid": {
        "count": 30,
        "revenue": 30000.00,
        "duration": 600
      }
    },
    "top_clients": [
      {
        "client_name": "Test Client",
        "transaction_count": 50,
        "total_revenue": 50000.00,
        "total_duration": 1200
      }
    ],
    "top_services": [
      {
        "service_name": "SMS Service",
        "usage_count": 100,
        "total_revenue": 100000.00,
        "total_duration": 2400
      }
    ],
    "hourly_trends": {
      "09:00": {
        "count": 10,
        "revenue": 10000.00,
        "duration": 200
      }
    },
    "status_breakdown": {
      "OK": {
        "count": 143,
        "revenue": 143000.00
      },
      "FAIL": {
        "count": 7,
        "revenue": 0.00
      }
    },
    "charge_breakdown": {
      "prepaid": {
        "count": 120,
        "revenue": 120000.00
      },
      "postpaid": {
        "count": 30,
        "revenue": 30000.00
      }
    },
    "date": "2024-01-01",
    "client_id": null,
    "service_id": null
  }
}
```

### Get Monthly Report Data
```http
GET /api/reports/monthly
```

**Query Parameters:**
- `month` (string, optional): Report month in YYYY-MM format (default: current month)
- `client_id` (integer, optional): Filter by client ID
- `service_id` (integer, optional): Filter by service ID

**Response:**
```json
{
  "success": true,
  "data": {
    "summary": {
      "Total Transactions": 4500,
      "Total Revenue": 4500000.00,
      "Total Duration": 108000,
      "Unique Users": 150,
      "Unique Clients": 50,
      "Avg Transactions/Day": 150.0,
      "Avg Revenue/Day": 150000.00,
      "Avg Duration/Day": 3600.0
    },
    "transaction_types": {
      "prepaid": {
        "count": 3600,
        "revenue": 3600000.00,
        "duration": 90000
      },
      "postpaid": {
        "count": 900,
        "revenue": 900000.00,
        "duration": 18000
      }
    },
    "client_types": {
      "Prepaid": {
        "count": 3600,
        "revenue": 3600000.00,
        "duration": 90000
      },
      "Postpaid": {
        "count": 900,
        "revenue": 900000.00,
        "duration": 18000
      }
    },
    "top_clients": [
      {
        "client_name": "Test Client",
        "transaction_count": 500,
        "total_revenue": 500000.00,
        "total_duration": 12000
      }
    ],
    "top_services": [
      {
        "service_name": "SMS Service",
        "usage_count": 2000,
        "total_revenue": 2000000.00,
        "total_duration": 48000
      }
    ],
    "daily_trends": {
      "2024-01-01": {
        "count": 150,
        "revenue": 150000.00,
        "duration": 3600
      }
    },
    "weekly_trends": {
      "Week 1": {
        "count": 1050,
        "revenue": 1050000.00,
        "duration": 25200
      }
    },
    "status_breakdown": {
      "OK": {
        "count": 4275,
        "revenue": 4275000.00
      },
      "FAIL": {
        "count": 225,
        "revenue": 0.00
      }
    },
    "charge_breakdown": {
      "prepaid": {
        "count": 3600,
        "revenue": 3600000.00
      },
      "postpaid": {
        "count": 900,
        "revenue": 900000.00
      }
    },
    "month": "2024-01",
    "client_id": null,
    "service_id": null
  }
}
```

### Export Daily Reports
```http
POST /api/reports/daily/export
```

**Request Body:**
```json
{
  "format": "excel",
  "date": "2024-01-01",
  "client_id": 1,
  "service_id": 1
}
```

**Response:**
- **Excel**: Returns Excel file download
- **PDF**: Returns PDF file download

### Export Monthly Reports
```http
POST /api/reports/monthly/export
```

**Request Body:**
```json
{
  "format": "pdf",
  "month": "2024-01",
  "client_id": 1,
  "service_id": 1
}
```

**Response:**
- **Excel**: Returns Excel file download
- **PDF**: Returns PDF file download

---

## Dashboard API

### Get Dashboard Data
```http
GET /api/dashboard
```

**Response:**
```json
{
  "success": true,
  "data": {
    "core_statistics": {
      "totalUsers": 25,
      "activeUsers": 23,
      "inactiveUsers": 2,
      "adminUsers": 3,
      "operatorUsers": 15,
      "viewerUsers": 7,
      "totalServices": 10,
      "activeServices": 9,
      "inactiveServices": 1,
      "internalServices": 6,
      "externalServices": 4,
      "totalClients": 50,
      "activeClients": 48,
      "inactiveClients": 2,
      "stagingClients": 10,
      "productionClients": 40
    },
    "recent_data": {
      "recentUsers": [
        {
          "id": 25,
          "name": "New User",
          "email": "newuser@example.com",
          "role": "operator",
          "is_active": 1,
          "created_at": "2024-01-01T12:00:00.000000Z"
        }
      ],
      "recentServices": [
        {
          "id": 10,
          "name": "New Service",
          "type": 1,
          "is_active": 1,
          "created_at": "2024-01-01T12:00:00.000000Z"
        }
      ],
      "recentClients": [
        {
          "id": 50,
          "client_name": "New Client",
          "type": 1,
          "is_active": 1,
          "is_staging": 0,
          "created_at": "2024-01-01T12:00:00.000000Z"
        }
      ],
      "recentBalances": [
        {
          "id": 100,
          "client_id": 50,
          "client_name": "New Client",
          "balance": 1000.00,
          "quota": 500.00,
          "created_at": "2024-01-01T12:00:00.000000Z"
        }
      ]
    },
    "relationships": {
      "topServices": [
        {
          "id": 1,
          "name": "SMS Service",
          "type": 1,
          "is_active": 1,
          "clients_count": 25
        }
      ],
      "topClients": [
        {
          "id": 1,
          "client_name": "Test Client",
          "is_staging": 0,
          "is_active": 1,
          "services_count": 8
        }
      ]
    },
    "enhanced_data": {
      "totalRevenue": 5000000.00,
      "totalTransactions": 10000,
      "avgTransactionValue": 500.00,
      "historiesChartData": {
        "labels": ["Jan 15", "Jan 16", "Jan 17", "Jan 18", "Jan 19", "Jan 20", "Jan 21"],
        "transactions": [120, 150, 180, 200, 160, 190, 220],
        "revenue": [120000, 150000, 180000, 200000, 160000, 190000, 220000],
        "successRate": [95.5, 96.2, 94.8, 97.1, 95.9, 96.5, 97.8],
        "topServices": [
          {
            "module_id": 1,
            "usage_count": 500,
            "revenue": 500000.00,
            "service": {
              "id": 1,
              "name": "SMS Service"
            }
          }
        ],
        "topClients": [
          {
            "client_id": 1,
            "transaction_count": 200,
            "revenue": 200000.00,
            "client": {
              "id": 1,
              "client_name": "Test Client"
            }
          }
        ],
        "statistics": {
          "totalTransactions": 1220,
          "totalRevenue": 1220000.00,
          "avgDailyTransactions": 174.3,
          "avgDailyRevenue": 174285.71,
          "period": "Jan 15 - Jan 21"
        }
      }
    }
  }
}
```

### Get Dashboard Statistics
```http
GET /api/dashboard/stats
```

**Response:**
```json
{
  "success": true,
  "data": {
    "totalUsers": 25,
    "activeUsers": 23,
    "inactiveUsers": 2,
    "adminUsers": 3,
    "operatorUsers": 15,
    "viewerUsers": 7,
    "totalServices": 10,
    "activeServices": 9,
    "inactiveServices": 1,
    "internalServices": 6,
    "externalServices": 4,
    "totalClients": 50,
    "activeClients": 48,
    "inactiveClients": 2,
    "stagingClients": 10,
    "productionClients": 40
  }
}
```

### Get Recent Context Data
```http
GET /api/dashboard/recent
```

**Response:**
```json
{
  "success": true,
  "data": {
    "recentUsers": [
      {
        "id": 25,
        "name": "New User",
        "email": "newuser@example.com",
        "role": "operator",
        "is_active": 1,
        "created_at": "2024-01-01T12:00:00.000000Z"
      }
    ],
    "recentServices": [
      {
        "id": 10,
        "name": "New Service",
        "type": 1,
        "is_active": 1,
        "created_at": "2024-01-01T12:00:00.000000Z"
      }
    ],
    "recentClients": [
      {
        "id": 50,
        "client_name": "New Client",
        "type": 1,
        "is_active": 1,
        "is_staging": 0,
        "created_at": "2024-01-01T12:00:00.000000Z"
      }
    ],
    "recentBalances": [
      {
        "id": 100,
        "client_id": 50,
        "client_name": "New Client",
        "balance": 1000.00,
        "quota": 500.00,
        "created_at": "2024-01-01T12:00:00.000000Z"
      }
    ]
  }
}
```

### Get Relationship Data
```http
GET /api/dashboard/relationships
```

**Response:**
```json
{
  "success": true,
  "data": {
    "topServices": [
      {
        "id": 1,
        "name": "SMS Service",
        "type": 1,
        "is_active": 1,
        "clients_count": 25
      }
    ],
    "topClients": [
      {
        "id": 1,
        "client_name": "Test Client",
        "is_staging": 0,
        "is_active": 1,
        "services_count": 8
      }
    ]
  }
}
```

---

## Error Codes

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Common Error Messages
- `"User not found"` - User with specified ID not found
- `"Client not found"` - Client with specified ID not found
- `"Service not found"` - Service with specified ID not found
- `"Currency not found"` - Currency with specified ID not found
- `"Price master not found"` - Price master with specified ID not found
- `"Price custom not found"` - Price custom with specified ID not found
- `"Balance topup not found"` - Balance topup with specified ID not found
- `"History not found"` - Transaction history with specified ID not found
- `"Validation failed"` - Request validation failed
- `"Unauthorized access"` - Insufficient permissions
- `"You cannot delete your own account"` - Self-deletion prevention

---

## Rate Limiting

### API Rate Limits
- **General API**: 1000 requests per hour per user
- **Authentication**: 5 login attempts per minute per IP
- **Export**: 10 export requests per hour per user
- **Reports**: 100 report requests per hour per user

### Rate Limit Headers
```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
```

---

## Pagination

### Query Parameters
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 10, max: 100)

### Response Headers
```
X-Pagination-Current-Page: 1
X-Pagination-Last-Page: 10
X-Pagination-Per-Page: 15
X-Pagination-Total: 150
X-Pagination-From: 1
X-Pagination-To: 15
```

---

## Filtering and Search

### Common Filters
- `search` (string): Text search across relevant fields
- `status` (string): Filter by active/inactive status
- `date_from` (date): Filter from date
- `date_to` (date): Filter to date
- `per_page` (integer): Number of items per page

### Search Fields
- **Users**: name, email, username
- **Clients**: client_name, contact, address
- **Services**: name
- **Currencies**: name, code
- **Price Masters**: service name, note
- **Price Customs**: service name, client name
- **Balance Topups**: client name, reference_number
- **Histories**: client name, service name

---

This comprehensive API documentation covers all endpoints, request/response formats, and features available in the Gateway Dashboard application.
