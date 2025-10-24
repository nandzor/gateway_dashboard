# Gateway Dashboard API - Postman Collection

## Overview
This Postman collection contains all the API endpoints for the Gateway Dashboard system, including balance management and authentication testing.

## Files Included
- `Gateway_Dashboard_API.postman_collection.json` - Main API collection
- `Local_Environment.postman_environment.json` - Local development environment
- `Staging_Environment.postman_environment.json` - Staging environment
- `API_Documentation.md` - Complete API documentation

## Quick Start

### 1. Import Collection
1. Open Postman
2. Click "Import" button
3. Select `Gateway_Dashboard_API.postman_collection.json`

### 2. Import Environments
1. Click "Import" button
2. Select both environment files:
   - `Local_Environment.postman_environment.json`
   - `Staging_Environment.postman_environment.json`

### 3. Select Environment
1. Click the environment dropdown in the top-right corner
2. Select either "Local Environment" or "Staging Environment"

### 4. Test API
1. Start with "Test Authentication" request
2. If successful, proceed with balance management requests

## Environment Variables

### Local Environment
- `base_url`: `http://localhost:8000`
- `api_token`: `tpnrAM9JA26xtqR31fipmpHR0MdjGbR6WAkIdH3AuhG8HBVI32HOsnqZqDxaai4D`
- `client_id`: `52`
- `test_amount`: `25000`

### Staging Environment
- `base_url`: `https://staging-api.gateway-dashboard.com`
- `api_token`: `stg_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`
- `client_id`: `100`
- `test_amount`: `50000`

## API Endpoints

### Balance Management
1. **Check Balance** - `POST /api/balance/check`
   - Check client balance information
   - Sample payload: `{"client_id": 52}`

2. **Get Balance History** - `POST /api/balance/history`
   - Retrieve transaction history with pagination
   - Sample payload: `{"client_id": 52, "limit": 10, "offset": 0}`

3. **Update Balance** - `POST /api/balance/update`
   - Add or deduct balance
   - Sample payload: `{"client_id": 52, "amount": 25000, "type": "credit", "note": "Top up balance"}`

### Authentication
4. **Test Authentication** - `GET /api/test-auth`
   - Verify API token validity
   - No payload required

## Sample Requests

### Check Balance
```bash
curl -X POST "http://localhost:8000/api/balance/check" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer tpnrAM9JA26xtqR31fipmpHR0MdjGbR6WAkIdH3AuhG8HBVI32HOsnqZqDxaai4D" \
  -d '{"client_id": 52}'
```

### Update Balance
```bash
curl -X POST "http://localhost:8000/api/balance/update" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer tpnrAM9JA26xtqR31fipmpHR0MdjGbR6WAkIdH3AuhG8HBVI32HOsnqZqDxaai4D" \
  -d '{"client_id": 52, "amount": 25000, "type": "credit", "note": "Top up balance"}'
```

## Response Examples

### Success Response
```json
{
    "success": true,
    "message": "Client balance retrieved successfully",
    "data": {
        "client": {
            "id": 52,
            "name": "test1",
            "type": "Prepaid",
            "is_active": true
        },
        "balance": {
            "current_balance": 150000,
            "quota": 10,
            "status": "positive"
        }
    },
    "meta": {
        "timestamp": "2025-10-24T13:09:11+07:00",
        "version": "1.0",
        "request_id": "eb4682b4-c325-4627-bcb6-bba8fe154d4c"
    }
}
```

### Error Response
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

## Testing Features

### Automated Tests
The collection includes automated tests that run after each request:
- Response status code validation
- Response structure validation
- Response time validation
- Meta field validation

### Pre-request Scripts
- Auto-generates unique request IDs for tracking
- Sets up environment variables automatically

## Troubleshooting

### Common Issues
1. **401 Unauthorized**: Check if API token is correct
2. **404 Not Found**: Verify client_id exists
3. **400 Bad Request**: Check request payload format
4. **Connection Error**: Verify base_url is correct

### Debug Steps
1. Test authentication first
2. Check environment variables
3. Verify request payload format
4. Check server logs for detailed errors

## Development Setup

### Local Development
1. Start Laravel development server: `php artisan serve`
2. Select "Local Environment" in Postman
3. Use local API token from environment

### Staging Testing
1. Deploy to staging server
2. Select "Staging Environment" in Postman
3. Update staging API token in environment

## Support
For issues or questions:
1. Check API documentation in `API_Documentation.md`
2. Review server logs for detailed error messages
3. Contact development team for assistance

## Version History
- v1.0 - Initial release with balance management endpoints
- v1.1 - Added authentication testing
- v1.2 - Added automated tests and pre-request scripts
