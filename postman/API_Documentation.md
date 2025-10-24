# Gateway Dashboard API Documentation

## Overview
Gateway Dashboard API provides endpoints for managing client balances, transaction history, and authentication.

## Base URLs
- **Local**: `http://localhost:8000`
- **Staging**: `https://staging-api.gateway-dashboard.com`

## Authentication
All API endpoints require authentication using a static token in the Authorization header:
```
Authorization: Bearer YOUR_API_TOKEN
```

## API Endpoints

### 1. Check Balance
**POST** `/api/balance/check`

Check client balance information including current balance, quota, and transaction status.

#### Request Body
```json
{
    "client_id": 52
}
```

#### Response (Success - 200)
```json
{
    "success": true,
    "message": "Client balance retrieved successfully",
    "data": {
        "client": {
            "id": 52,
            "name": "test1",
            "type": "Prepaid",
            "is_active": true,
            "created_at": "2025-03-17T02:14:52.796644Z"
        },
        "balance": {
            "current_balance": 150000,
            "quota": 10,
            "status": "positive",
            "last_updated": "2025-10-24T06:02:44.000000Z"
        },
        "summary": {
            "has_balance": true,
            "is_zero": false,
            "is_negative": false,
            "can_transact": true
        }
    },
    "meta": {
        "timestamp": "2025-10-24T13:09:11+07:00",
        "version": "1.0",
        "request_id": "eb4682b4-c325-4627-bcb6-bba8fe154d4c",
        "query_count": 0,
        "memory_usage": "1.37 MB",
        "execution_time": "31.14ms"
    }
}
```

#### Response (Error - 404)
```json
{
    "success": false,
    "message": "Client not found",
    "data": null,
    "meta": {
        "timestamp": "2025-10-24T13:15:30+07:00",
        "version": "1.0",
        "request_id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890"
    }
}
```

---

### 2. Get Balance History
**POST** `/api/balance/history`

Retrieve client's transaction history with pagination support.

#### Request Body
```json
{
    "client_id": 52,
    "limit": 10,
    "offset": 0
}
```

#### Parameters
- `client_id` (required): Integer - Client ID
- `limit` (optional): Integer - Number of records to return (default: 20, max: 100)
- `offset` (optional): Integer - Number of records to skip (default: 0)

#### Response (Success - 200)
```json
{
    "success": true,
    "message": "Balance history retrieved successfully",
    "data": {
        "client": {
            "id": 52,
            "name": "test1"
        },
        "history": [
            {
                "transaction_id": "TXN001",
                "amount": 5000,
                "type": "debit",
                "service": "OCR",
                "date": "2025-10-24T10:30:00.000000Z",
                "status": "OK"
            },
            {
                "transaction_id": "TXN002",
                "amount": 10000,
                "type": "credit",
                "service": "SMS",
                "date": "2025-10-23T15:45:00.000000Z",
                "status": "OK"
            }
        ],
        "pagination": {
            "limit": 10,
            "offset": 0,
            "count": 2
        }
    },
    "meta": {
        "timestamp": "2025-10-24T13:20:15+07:00",
        "version": "1.0",
        "request_id": "f1e2d3c4-b5a6-9870-fedc-ba0987654321"
    }
}
```

---

### 3. Update Balance
**POST** `/api/balance/update`

Update client balance by adding or deducting amount.

#### Request Body
```json
{
    "client_id": 52,
    "amount": 25000,
    "type": "credit",
    "note": "Top up balance"
}
```

#### Parameters
- `client_id` (required): Integer - Client ID
- `amount` (required): Numeric - Amount to add/deduct (must be >= 0)
- `type` (required): String - Transaction type ("credit" or "debit")
- `note` (optional): String - Transaction note/description

#### Response (Success - 200)
```json
{
    "success": true,
    "message": "Balance updated successfully",
    "data": {
        "client": {
            "id": 52,
            "name": "test1"
        },
        "balance": {
            "previous_balance": 150000,
            "new_balance": 175000,
            "amount_changed": 25000,
            "type": "credit"
        },
        "transaction": {
            "id": "TXN003",
            "note": "Top up balance",
            "created_at": "2025-10-24T13:25:00.000000Z"
        }
    },
    "meta": {
        "timestamp": "2025-10-24T13:25:00+07:00",
        "version": "1.0",
        "request_id": "c3d4e5f6-a7b8-9012-cdef-123456789abc"
    }
}
```

#### Response (Error - 400)
```json
{
    "success": false,
    "message": "Validation failed",
    "data": {
        "errors": {
            "client_id": ["The client id must be an integer."],
            "amount": ["The amount must be at least 0."],
            "type": ["The selected type is invalid."]
        }
    },
    "meta": {
        "timestamp": "2025-10-24T13:26:00+07:00",
        "version": "1.0",
        "request_id": "d4e5f6a7-b8c9-0123-defa-234567890bcd"
    }
}
```

---

### 4. Test Authentication
**GET** `/api/test-auth`

Test if the provided API token is valid.

#### Headers
```
Authorization: Bearer YOUR_API_TOKEN
```

#### Response (Success - 200)
```json
{
    "success": true,
    "message": "Authentication successful",
    "data": {
        "authenticated": true,
        "timestamp": "2025-10-24T13:30:00+07:00"
    },
    "meta": {
        "timestamp": "2025-10-24T13:30:00+07:00",
        "version": "1.0",
        "request_id": "e5f6a7b8-c9d0-1234-efab-345678901cde"
    }
}
```

#### Response (Error - 401)
```json
{
    "success": false,
    "message": "Invalid API token",
    "data": null,
    "meta": {
        "timestamp": "2025-10-24T13:31:00+07:00",
        "version": "1.0",
        "request_id": "f6a7b8c9-d0e1-2345-fabc-456789012def"
    }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 400 | Bad Request - Validation error |
| 401 | Unauthorized - Invalid API token |
| 404 | Not Found - Resource not found |
| 500 | Internal Server Error |

## Response Format

All API responses follow this standard format:

```json
{
    "success": boolean,
    "message": string,
    "data": object|null,
    "meta": {
        "timestamp": string,
        "version": string,
        "request_id": string,
        "query_count": number,
        "memory_usage": string,
        "execution_time": string
    }
}
```

## Rate Limiting
- No rate limiting currently implemented
- Consider implementing rate limiting for production use

## Postman Collection
Import the provided Postman collection files:
- `Gateway_Dashboard_API.postman_collection.json`
- `Local_Environment.postman_environment.json`
- `Staging_Environment.postman_environment.json`

## Testing
Use the provided Postman collection with the following environments:
- **Local**: For development testing
- **Staging**: For pre-production testing

## Support
For API support and questions, contact the development team.
