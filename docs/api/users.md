# User API Endpoints

This document describes the User API endpoints implemented following Laravel best practices.

## Authentication

All endpoints require authentication using Laravel Sanctum. You must include the `Authorization: Bearer {token}` header in your requests.

## Endpoints

### Get Users List

```
GET /api/users
```

#### Query Parameters

| Parameter | Type | Description | Default |
|-----------|------|-------------|---------|
| per_page | integer | Number of users per page | 15 |

#### Response

```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "status": true,
      "email_verified_at": "2023-01-01T00:00:00.000000Z",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://example.com/api/users?page=1",
    "last": "http://example.com/api/users?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://example.com/api/users",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

### Get User Detail

```
GET /api/users/{id}
```

#### URL Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | The ID of the user |

#### Response

```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "status": true,
    "email_verified_at": "2023-01-01T00:00:00.000000Z",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```

#### Error Responses

When a user is not found, the API will return a 404 status code:

```json
{
  "message": "No query results for model [App\\Models\\User] 999999"
}
```