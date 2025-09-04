# Matrimonial API Documentation

## 1. Overview

The Matrimonial API provides a comprehensive set of endpoints for managing matrimonial profiles in a community-based application. It allows users to create, read, update, and delete matrimonial profiles, as well as manage profile images and search for potential matches based on various criteria.

All API endpoints are RESTful and return JSON responses. The API follows standard HTTP status codes and includes pagination for list endpoints.

## 2. Authentication

All matrimonial API endpoints require authentication using Laravel Sanctum tokens. To authenticate:

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

## 3. Error Responses

The API uses standard HTTP status codes to indicate the success or failure of requests:

- `200` - Success (GET, PUT, PATCH)
- `201` - Created (POST)
- `204` - No Content (DELETE)
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity (Validation errors)
- `500` - Internal Server Error

Error responses follow this format:
```json
{
  "message": "Error description"
}
```

Validation errors follow this format:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message for the field"
    ]
  }
}
```

## 4. Endpoints

### 4.1 List Matrimonial Profiles

Get a paginated list of all active matrimonial profiles with optional filtering.

```
GET /api/matrimonial-profiles
```

#### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `marital_status` | string | Filter by marital status (single, divorced, widowed, separated) |
| `religion` | string | Filter by religion (partial match) |
| `caste` | string | Filter by caste (partial match) |
| `min_age` | integer | Minimum age filter |
| `max_age` | integer | Maximum age filter |
| `country` | string | Filter by country (partial match) |
| `state` | string | Filter by state (partial match) |
| `city` | string | Filter by city (partial match) |
| `per_page` | integer | Number of items per page (default: 15) |

#### Sample Request

```bash
GET /api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10
```

#### Sample Response

```json
{
  "data": [
    {
      "id": 1,
      "user_id": 10,
      "family_member_id": 5,
      "age": 28,
      "height": 170,
      "weight": 65,
      "marital_status": "single",
      "education": "Bachelor of Technology",
      "occupation": "Software Engineer",
      "annual_income": 800000.00,
      "currency": "INR",
      "religion": "Hindu",
      "caste": "Brahmin",
      "sub_caste": "Gaur",
      "mother_tongue": "Hindi",
      "country": "India",
      "state": "Delhi",
      "city": "New Delhi",
      "about_me": "I am a software engineer looking for a life partner who shares my values and interests.",
      "partner_preferences": "Looking for someone between 25-32 years of age, educated and professionally established.",
      "is_active": true,
      "user": {
        "id": 10,
        "name": "John Doe"
      },
      "profile_images": [
        {
          "id": 1,
          "url": "http://example.com/storage/1/profile_images/image1.jpg",
          "thumbnail_url": "http://example.com/storage/1/profile_images/conversions/image1-thumb.jpg"
        }
      ],
      "created_at": "2023-01-15T10:30:00.000000Z",
      "updated_at": "2023-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=1",
    "last": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=3",
    "prev": null,
    "next": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=2",
        "label": "2",
        "active": false
      },
      {
        "url": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=3",
        "label": "3",
        "active": false
      },
      {
        "url": "http://example.com/api/matrimonial-profiles?religion=Hindu&min_age=25&max_age=35&per_page=10&page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://example.com/api/matrimonial-profiles",
    "per_page": 10,
    "to": 10,
    "total": 25
  }
}
```

### 4.2 List Matrimonial Profiles by Gender

Get a paginated list of active matrimonial profiles filtered by gender with optional additional filtering.

```
GET /api/matrimonial-profiles/by-gender/{gender}
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `gender` | string | Gender to filter by (male, female, other) |

#### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `marital_status` | string | Filter by marital status (single, divorced, widowed, separated) |
| `religion` | string | Filter by religion (partial match) |
| `caste` | string | Filter by caste (partial match) |
| `min_age` | integer | Minimum age filter |
| `max_age` | integer | Maximum age filter |
| `country` | string | Filter by country (partial match) |
| `state` | string | Filter by state (partial match) |
| `city` | string | Filter by city (partial match) |
| `per_page` | integer | Number of items per page (default: 15) |

#### Sample Request

```bash
GET /api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5
```

#### Sample Response

```json
{
  "data": [
    {
      "id": 2,
      "user_id": 15,
      "family_member_id": 8,
      "age": 30,
      "height": 165,
      "weight": 55,
      "marital_status": "single",
      "education": "Master of Arts",
      "occupation": "Teacher",
      "annual_income": 600000.00,
      "currency": "INR",
      "religion": "Hindu",
      "caste": "Maratha",
      "sub_caste": "",
      "mother_tongue": "Marathi",
      "country": "India",
      "state": "Maharashtra",
      "city": "Mumbai",
      "about_me": "I am a teacher looking for a kind and understanding partner.",
      "partner_preferences": "Looking for someone who is family-oriented and professionally established.",
      "is_active": true,
      "user": {
        "id": 15,
        "name": "Jane Smith"
      },
      "profile_images": [
        {
          "id": 2,
          "url": "http://example.com/storage/2/profile_images/image2.jpg",
          "thumbnail_url": "http://example.com/storage/2/profile_images/conversions/image2-thumb.jpg"
        },
        {
          "id": 3,
          "url": "http://example.com/storage/2/profile_images/image3.jpg",
          "thumbnail_url": "http://example.com/storage/2/profile_images/conversions/image3-thumb.jpg"
        }
      ],
      "created_at": "2023-02-20T14:45:00.000000Z",
      "updated_at": "2023-02-20T14:45:00.000000Z"
    }
  ],
  "links": {
    "first": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=1",
    "last": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=2",
    "prev": null,
    "next": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=2",
        "label": "2",
        "active": false
      },
      {
        "url": "http://example.com/api/matrimonial-profiles/by-gender/female?min_age=25&max_age=35&city=Mumbai&per_page=5&page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://example.com/api/matrimonial-profiles/by-gender/female",
    "per_page": 5,
    "to": 1,
    "total": 2
  }
}
```

### 4.3 Get a Specific Matrimonial Profile

Get details of a specific matrimonial profile by ID.

```
GET /api/matrimonial-profiles/{id}
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | ID of the matrimonial profile |

#### Sample Request

```bash
GET /api/matrimonial-profiles/1
```

#### Sample Response

```json
{
  "id": 1,
  "user_id": 10,
  "family_member_id": 5,
  "age": 28,
  "height": 170,
  "weight": 65,
  "marital_status": "single",
  "education": "Bachelor of Technology",
  "occupation": "Software Engineer",
  "annual_income": 800000.00,
  "currency": "INR",
  "religion": "Hindu",
  "caste": "Brahmin",
  "sub_caste": "Gaur",
  "mother_tongue": "Hindi",
  "country": "India",
  "state": "Delhi",
  "city": "New Delhi",
  "about_me": "I am a software engineer looking for a life partner who shares my values and interests.",
  "partner_preferences": "Looking for someone between 25-32 years of age, educated and professionally established.",
  "is_active": true,
  "user": {
    "id": 10,
    "name": "John Doe"
  },
  "profile_images": [
    {
      "id": 1,
      "url": "http://example.com/storage/1/profile_images/image1.jpg",
      "thumbnail_url": "http://example.com/storage/1/profile_images/conversions/image1-thumb.jpg"
    }
  ],
  "created_at": "2023-01-15T10:30:00.000000Z",
  "updated_at": "2023-01-15T10:30:00.000000Z"
}
```

### 4.4 Create a Matrimonial Profile

Create a new matrimonial profile.

```
POST /api/matrimonial-profiles
```

#### Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `user_id` | integer | Yes | ID of the user |
| `family_member_id` | integer | No | ID of the family member (if applicable) |
| `age` | integer | Yes | Age (18-100) |
| `height` | integer | No | Height in cm (100-250) |
| `weight` | integer | No | Weight in kg (30-200) |
| `marital_status` | string | Yes | Marital status (single, divorced, widowed, separated) |
| `education` | string | No | Education details (max 255 characters) |
| `occupation` | string | No | Occupation (max 255 characters) |
| `annual_income` | decimal | No | Annual income |
| `currency` | string | No | Currency code (max 3 characters) |
| `religion` | string | No | Religion (max 255 characters) |
| `caste` | string | No | Caste (max 255 characters) |
| `sub_caste` | string | No | Sub-caste (max 255 characters) |
| `mother_tongue` | string | No | Mother tongue (max 255 characters) |
| `country` | string | No | Country (max 255 characters) |
| `state` | string | No | State (max 255 characters) |
| `city` | string | No | City (max 255 characters) |
| `about_me` | string | No | About me (max 1000 characters) |
| `partner_preferences` | string | No | Partner preferences (max 1000 characters) |
| `is_active` | boolean | No | Whether the profile is active (default: true) |

#### Sample Request

```bash
POST /api/matrimonial-profiles
Content-Type: application/json

{
  "user_id": 10,
  "age": 28,
  "height": 170,
  "weight": 65,
  "marital_status": "single",
  "education": "Bachelor of Technology",
  "occupation": "Software Engineer",
  "annual_income": 800000,
  "currency": "INR",
  "religion": "Hindu",
  "caste": "Brahmin",
  "sub_caste": "Gaur",
  "mother_tongue": "Hindi",
  "country": "India",
  "state": "Delhi",
  "city": "New Delhi",
  "about_me": "I am a software engineer looking for a life partner who shares my values and interests.",
  "partner_preferences": "Looking for someone between 25-32 years of age, educated and professionally established.",
  "is_active": true
}
```

#### Sample Response

```json
{
  "id": 1,
  "user_id": 10,
  "family_member_id": null,
  "age": 28,
  "height": 170,
  "weight": 65,
  "marital_status": "single",
  "education": "Bachelor of Technology",
  "occupation": "Software Engineer",
  "annual_income": 800000.00,
  "currency": "INR",
  "religion": "Hindu",
  "caste": "Brahmin",
  "sub_caste": "Gaur",
  "mother_tongue": "Hindi",
  "country": "India",
  "state": "Delhi",
  "city": "New Delhi",
  "about_me": "I am a software engineer looking for a life partner who shares my values and interests.",
  "partner_preferences": "Looking for someone between 25-32 years of age, educated and professionally established.",
  "is_active": true,
  "user": {
    "id": 10,
    "name": "John Doe"
  },
  "profile_images": [],
  "created_at": "2023-01-15T10:30:00.000000Z",
  "updated_at": "2023-01-15T10:30:00.000000Z"
}
```

### 4.5 Update a Matrimonial Profile

Update an existing matrimonial profile.

```
PUT /api/matrimonial-profiles/{id}
```

or

```
PATCH /api/matrimonial-profiles/{id}
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | ID of the matrimonial profile |

#### Request Body

All fields are optional for updates. See the create endpoint for field descriptions.

#### Sample Request

```bash
PUT /api/matrimonial-profiles/1
Content-Type: application/json

{
  "marital_status": "married",
  "is_active": false
}
```

#### Sample Response

```json
{
  "id": 1,
  "user_id": 10,
  "family_member_id": null,
  "age": 28,
  "height": 170,
  "weight": 65,
  "marital_status": "married",
  "education": "Bachelor of Technology",
  "occupation": "Software Engineer",
  "annual_income": 800000.00,
  "currency": "INR",
  "religion": "Hindu",
  "caste": "Brahmin",
  "sub_caste": "Gaur",
  "mother_tongue": "Hindi",
  "country": "India",
  "state": "Delhi",
  "city": "New Delhi",
  "about_me": "I am a software engineer looking for a life partner who shares my values and interests.",
  "partner_preferences": "Looking for someone between 25-32 years of age, educated and professionally established.",
  "is_active": false,
  "user": {
    "id": 10,
    "name": "John Doe"
  },
  "profile_images": [],
  "created_at": "2023-01-15T10:30:00.000000Z",
  "updated_at": "2023-01-20T15:45:00.000000Z"
}
```

### 4.6 Delete a Matrimonial Profile

Delete a matrimonial profile.

```
DELETE /api/matrimonial-profiles/{id}
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | ID of the matrimonial profile |

#### Sample Request

```bash
DELETE /api/matrimonial-profiles/1
```

#### Sample Response

```json
(null with HTTP 204 No Content status)
```

### 4.7 Upload Profile Images

Upload images for a matrimonial profile.

```
POST /api/matrimonial-profiles/{matrimonialProfile}/images
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `matrimonialProfile` | integer | ID of the matrimonial profile |

#### Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `images` | array | Yes | Array of image files (max 10) |
| `images.*` | file | Yes | Image file (jpeg, png, jpg, gif, max 2MB) |

#### Sample Request

```bash
POST /api/matrimonial-profiles/1/images
Content-Type: multipart/form-data

images[]: (file data)
images[]: (file data)
```

#### Sample Response

```json
{
  "message": "Images uploaded successfully",
  "images": [
    {
      "id": 1,
      "url": "http://example.com/storage/1/profile_images/image1.jpg",
      "thumbnail_url": "http://example.com/storage/1/profile_images/conversions/image1-thumb.jpg"
    },
    {
      "id": 2,
      "url": "http://example.com/storage/1/profile_images/image2.jpg",
      "thumbnail_url": "http://example.com/storage/1/profile_images/conversions/image2-thumb.jpg"
    }
  ]
}
```

### 4.8 Delete a Profile Image

Delete an image from a matrimonial profile.

```
DELETE /api/matrimonial-profiles/{matrimonialProfile}/images/{mediaId}
```

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `matrimonialProfile` | integer | ID of the matrimonial profile |
| `mediaId` | integer | ID of the media item to delete |

#### Sample Request

```bash
DELETE /api/matrimonial-profiles/1/images/1
```

#### Sample Response

```json
{
  "message": "Image deleted successfully"
}
```

## 5. Data Models

### 5.1 Matrimonial Profile

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `id` | integer | No | Unique identifier |
| `user_id` | integer | Yes | ID of the user who owns this profile |
| `family_member_id` | integer | No | ID of the family member (if profile is for a family member) |
| `age` | integer | Yes | Age in years (18-100) |
| `height` | integer | No | Height in centimeters (100-250) |
| `weight` | integer | No | Weight in kilograms (30-200) |
| `marital_status` | string | Yes | Marital status (single, divorced, widowed, separated) |
| `education` | string | No | Education details (max 255 characters) |
| `occupation` | string | No | Occupation (max 255 characters) |
| `annual_income` | decimal | No | Annual income with 2 decimal places |
| `currency` | string | No | Currency code (max 3 characters) |
| `religion` | string | No | Religion (max 255 characters) |
| `caste` | string | No | Caste (max 255 characters) |
| `sub_caste` | string | No | Sub-caste (max 255 characters) |
| `mother_tongue` | string | No | Mother tongue (max 255 characters) |
| `country` | string | No | Country (max 255 characters) |
| `state` | string | No | State (max 255 characters) |
| `city` | string | No | City (max 255 characters) |
| `about_me` | string | No | About me text (max 1000 characters) |
| `partner_preferences` | string | No | Partner preferences text (max 1000 characters) |
| `is_active` | boolean | No | Whether the profile is active (default: true) |
| `created_at` | datetime | No | Creation timestamp |
| `updated_at` | datetime | No | Last update timestamp |

### 5.2 User

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Unique identifier |
| `name` | string | User's name |

### 5.3 Profile Image

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Unique identifier |
| `url` | string | Full size image URL |
| `thumbnail_url` | string | Thumbnail image URL (150x150) |

## 6. Example Use Cases

### 6.1 Searching for Potential Matches

A user wants to find potential matches based on specific criteria:

```bash
# Find single Hindu women aged 25-30 in Mumbai
GET /api/matrimonial-profiles/by-gender/female?marital_status=single&religion=Hindu&min_age=25&max_age=30&city=Mumbai
```

### 6.2 Creating a Profile with Images

A user wants to create a matrimonial profile and upload profile pictures:

```bash
# Step 1: Create the profile
POST /api/matrimonial-profiles
Content-Type: application/json

{
  "user_id": 20,
  "age": 27,
  "marital_status": "single",
  "education": "Master of Business Administration",
  "occupation": "Marketing Manager",
  "annual_income": 900000,
  "currency": "INR",
  "religion": "Hindu",
  "caste": "Agarwal",
  "mother_tongue": "Hindi",
  "country": "India",
  "state": "Maharashtra",
  "city": "Mumbai",
  "about_me": "Successful marketing professional looking for a life partner.",
  "partner_preferences": "Looking for someone educated and family-oriented."
}

# Step 2: Upload profile images
POST /api/matrimonial-profiles/3/images
Content-Type: multipart/form-data

images[]: (file data for image1.jpg)
images[]: (file data for image2.jpg)
```

### 6.3 Updating Profile Information

A user wants to update their profile information after a career change:

```bash
PUT /api/matrimonial-profiles/3
Content-Type: application/json

{
  "occupation": "Senior Marketing Manager",
  "annual_income": 1200000
}
```

### 6.4 Deactivating a Profile

A user wants to temporarily hide their profile from search results:

```bash
PUT /api/matrimonial-profiles/3
Content-Type: application/json

{
  "is_active": false
}
```