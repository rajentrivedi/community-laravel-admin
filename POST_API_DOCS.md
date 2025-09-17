# Post API Documentation

This documentation covers all endpoints related to the Post resource in the community app API. Posts are user-generated content that can include text and images, and can be associated with communities.

## Endpoints

### Get All Posts
```
GET /api/posts
```

#### Query Parameters
| Parameter | Type | Description |
|-----------|------|-------------|
| `community_id` | integer | Filter posts by community ID |
| `author_id` | integer | Filter posts by author ID |
| `type` | string | Filter posts by type |
| `search` | string | Search in title and body |
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
      "body": "We're excited to announce our upcoming community event...",
      "type": "news",
      "published_at": "2023-06-15T08:00:00.000000Z",
      "is_pinned": false,
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
          "url": "https://example.com/storage/images/post-1.jpg",
          "name": "post-1",
          "mime_type": "image/jpeg"
        }
      ]
    }
  ],
  "links": {
    "first": "http://example.com/api/posts?page=1",
    "last": "http://example.com/api/posts?page=1",
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
        "url": "http://example.com/api/posts?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://example.com/api/posts",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

### Create Post
```
POST /api/posts
```

#### Request Body
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `title` | string | Yes | Post title (max 255 characters) |
| `body` | string | Yes | Post content |
| `published_at` | datetime | No | Publication date |
| `community_id` | integer | Yes | ID of the community |
| `type` | string | No | Type of post (default: 'news') |
| `is_pinned` | boolean | No | Whether the post is pinned |
| `images` | array | No | Array of image files (max 5) |

#### Example Request
```json
{
  "title": "Community Event Announcement",
  "body": "We're excited to announce our upcoming community event...",
  "published_at": "2023-06-15T08:00:00.000Z",
  "community_id": 1,
  "type": "news",
  "is_pinned": false
}
```

#### Response
```json
{
  "id": 1,
  "title": "Community Event Announcement",
  "body": "We're excited to announce our upcoming community event...",
  "type": "news",
  "published_at": "2023-06-15T08:00:00.000000Z",
 "is_pinned": false,
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

### Get Specific Post
```
GET /api/posts/{id}
```

#### Response
```json
{
  "id": 1,
  "title": "Community Event Announcement",
  "body": "We're excited to announce our upcoming community event...",
  "type": "news",
  "published_at": "2023-06-15T08:00:00.000000Z",
  "is_pinned": false,
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
      "url": "https://example.com/storage/images/post-1.jpg",
      "name": "post-1",
      "mime_type": "image/jpeg"
    }
  ]
}
```

### Update Post
```
PUT/PATCH /api/posts/{id}
```

#### Request Body
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `title` | string | No | Post title (max 255 characters) |
| `body` | string | No | Post content |
| `published_at` | datetime | No | Publication date |
| `community_id` | integer | No | ID of the community |
| `type` | string | No | Type of post |
| `is_pinned` | boolean | No | Whether the post is pinned |
| `images` | array | No | Array of image files (max 5) |

#### Response
```json
{
  "id": 1,
  "title": "Updated Community Event Announcement",
  "body": "We're excited to announce our upcoming updated community event...",
  "type": "news",
  "published_at": "2023-06-15T08:00:00.000000Z",
  "is_pinned": false,
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
      "url": "https://example.com/storage/images/post-1.jpg",
      "name": "post-1",
      "mime_type": "image/jpeg"
    }
  ]
}
```

### Delete Post
```
DELETE /api/posts/{id}
```

#### Response
```
HTTP/1.1 204 No Content
```

### Upload Image to Post
```
POST /api/posts/{id}/image
```

#### Request Body
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `image` | file | Yes | Image file (JPEG, PNG, JPG, GIF, WEBP, max 2MB) |

#### Response
```json
{
  "message": "Image uploaded successfully",
  "image_url": "https://example.com/storage/images/post-1.jpg"
}
```

### Delete Image from Post
```
DELETE /api/posts/{id}/image/{mediaId}
```

#### Response
```json
{
  "message": "Image deleted successfully"
}
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
  "message": "No query results for model [App\\Models\\Post] 999"
}
```

### Like a Post
```
POST /api/posts/{id}/like
```

#### Response
```json
{
  "message": "Post liked successfully",
  "like_count": 5
}
```

### Unlike a Post
```
DELETE /api/posts/{id}/like
```

#### Response
```json
{
  "message": "Post unliked successfully",
  "like_count": 4
}
```