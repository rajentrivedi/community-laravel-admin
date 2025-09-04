# Settings API Documentation

## Overview

The Settings API provides endpoints to access application settings, including payment gateway configuration. All endpoints require authentication.

## Authentication

All settings API endpoints require authentication using Laravel Sanctum tokens. To authenticate:

1. Obtain an API token by logging in:
   ```bash
   POST /api/login
   Content-Type: application/json
   
   {
     "email": "user@example.com",
     "password": "password"
   }
   ```

2. Include the token in subsequent requests:
   ```bash
   Authorization: Bearer YOUR_API_TOKEN
   ```

## Endpoints

### Get Payment Gateway Settings

Get the payment gateway key ID and key secret.

```
GET /api/settings/payment-gateway
```

#### Sample Request

```bash
curl -X GET \
  http://localhost/api/settings/payment-gateway \
  -H 'Authorization: Bearer YOUR_API_TOKEN' \
  -H 'Accept: application/json'
```

#### Sample Response

```json
{
  "key_id": "rzp_test_XYzABC123",
  "key_secret": "abcdefgHIJKLMN1234567"
}
```

#### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `key_id` | string | Payment gateway key ID |
| `key_secret` | string | Payment gateway key secret |

### Get All Settings

Get all application settings as key-value pairs.

```
GET /api/settings/all
```

#### Sample Request

```bash
curl -X GET \
  http://localhost/api/settings/all \
  -H 'Authorization: Bearer YOUR_API_TOKEN' \
  -H 'Accept: application/json'
```

#### Sample Response

```json
{
  "payment_gateway_key_id": "rzp_test_XYzABC123",
  "payment_gateway_key_secret": "abcdefgHIJKLMN1234567",
  "site_name": "Community App",
  "site_email": "admin@example.com"
}
```

#### HTTP Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 401 | Unauthorized - Missing or invalid authentication token |
| 500 | Internal Server Error |

## Error Responses

The API uses standard HTTP status codes to indicate the success or failure of requests:

- `200` - Success
- `401` - Unauthorized
- `500` - Internal Server Error

Error responses follow this format:
```json
{
  "message": "Error description"
}
```

## Usage Examples

### JavaScript (using fetch)

```javascript
const getPaymentGatewaySettings = async () => {
  try {
    const response = await fetch('/api/settings/payment-gateway', {
      method: 'GET',
      headers: {
        'Authorization': 'Bearer YOUR_API_TOKEN',
        'Accept': 'application/json',
      },
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log('Key ID:', data.key_id);
    console.log('Key Secret:', data.key_secret);
    
    return data;
  } catch (error) {
    console.error('Error fetching payment gateway settings:', error);
  }
};
```

### PHP (using Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'http://localhost']);

try {
    $response = $client->request('GET', '/api/settings/payment-gateway', [
        'headers' => [
            'Authorization' => 'Bearer YOUR_API_TOKEN',
            'Accept' => 'application/json',
        ],
    ]);
    
    $data = json_decode($response->getBody(), true);
    echo "Key ID: " . $data['key_id'] . "\n";
    echo "Key Secret: " . $data['key_secret'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```