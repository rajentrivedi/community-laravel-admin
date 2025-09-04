# News API Documentation

This documentation covers all endpoints related to the News resource in the community app API.

## Endpoints

### Get All News
```
GET /api/news
```

#### Query Parameters
| Parameter | Type | Description |
|-----------|------|-------------|
| `community_id` | integer | Filter news by community ID |
| `author_id` | integer | Filter news by author ID |
| `search` | string | Search in title and content |
| `sort_by` | string | Sort by column (created_at, updated_at, published_at, title) |
| `sort_direction` | string | Sort direction (asc, desc) |
| `per_page` | integer | Number of items per page (default: 15) |

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "title": "Community Event Announcement",
      "content": "We're excited to announce our upcoming community event...",
      "published_at": "2023-06-15T08:00:00.000000Z",
      "created_at": "2023-06-15T08:00:00.000000Z",
      "updated_at": "2023-06-15T08:00:00.000000Z",
      "author": {
        "id": 1,
        "name": "John Doe"
      },
      "community": {
        "id": 1,
        "name": "Main Community"
      },
      "images": [
        {
          "id": 1,
          "url": "https://example.com/storage/images/news-1.jpg",
          "name": "news-1",
          "mime_type": "image/jpeg"
        }
      ]
    }
  ],
  "links": {
    "first": "http://example.com/api/news?page=1",
    "last": "http://example.com/api/news?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://example.com/api/news?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://example.com/api/news",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

### Create News
```
POST /api/news
```

#### Request Body
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `title` | string | Yes | News title (max 255 characters) |
| `content` | string | Yes | News content |
| `published_at` | datetime | No | Publication date |
| `community_id` | integer | Yes | ID of the community |
| `images` | array | No | Array of image files (max 5) |

#### Example Request
```json
{
  "title": "Community Event Announcement",
  "content": "We're excited to announce our upcoming community event...",
  "published_at": "2023-06-15T08:00:00.000Z",
  "community_id": 1
}
```

#### Response
```json
{
  "id": 1,
  "title": "Community Event Announcement",
  "content": "We're excited to announce our upcoming community event...",
  "published_at": "2023-06-15T08:00:00.000000Z",
  "created_at": "2023-06-15T08:00:00.000000Z",
  "updated_at": "2023-06-15T08:00:00.000000Z",
  "author": {
    "id": 1,
    "name": "John Doe"
  },
  "community": {
    "id": 1,
    "name": "Main Community"
  },
  "images": []
}
```

### Get Specific News
```
GET /api/news/{id}
```

#### Response
```json
{
  "id": 1,
  "title": "Community Event Announcement",
  "content": "We're excited to announce our upcoming community event...",
  "published_at": "2023-06-15T08:00:00.000000Z",
  "created_at": "2023-06-15T08:00:00.000000Z",
  "updated_at": "2023-06-15T08:00:00.000000Z",
  "author": {
    "id": 1,
    "name": "John Doe"
  },
  "community": {
    "id": 1,
    "name": "Main Community"
  },
  "images": [
    {
      "id": 1,
      "url": "https://example.com/storage/images/news-1.jpg",
      "name": "news-1",
      "mime_type": "image/jpeg"
    }
  ]
}
```

### Update News
```
PUT/PATCH /api/news/{id}
```

#### Request Body
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `title` | string | No | News title (max 255 characters) |
| `content` | string | No | News content |
| `published_at` | datetime | No | Publication date |
| `community_id` | integer | No | ID of the community |
| `images` | array | No | Array of image files (max 5) |

#### Response
```json
{
  "id": 1,
  "title": "Updated Community Event Announcement",
  "content": "We're excited to announce our upcoming updated community event...",
  "published_at": "2023-06-15T08:00:00.000000Z",
  "created_at": "2023-06-15T08:00:00.000000Z",
  "updated_at": "2023-06-16T10:00:00.000000Z",
  "author": {
    "id": 1,
    "name": "John Doe"
  },
  "community": {
    "id": 1,
    "name": "Main Community"
  },
  "images": [
    {
      "id": 1,
      "url": "https://example.com/storage/images/news-1.jpg",
      "name": "news-1",
      "mime_type": "image/jpeg"
    }
  ]
}
```

### Delete News
```
DELETE /api/news/{id}
```

#### Response
```
HTTP/1.1 204 No Content
```

## Error Responses

All endpoints may return the following error responses:

### Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": [
      "The title field is required."
    ],
    "community_id": [
      "The selected community id is invalid."
    ]
  }
}
```

### Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found
```json
{
  "message": "No query results for model [App\\Models\\News] 999"
}
```