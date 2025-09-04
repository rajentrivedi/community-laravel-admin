# News API Implementation Summary

## Overview
This document provides a summary of the News API implementation for the Community App. The implementation follows Laravel best practices and provides a complete RESTful API for managing news items.

## Components Created

### 1. NewsPolicy
- Location: `app/Policies/NewsPolicy.php`
- Handles authorization for news operations
- Only authors can update or delete their own news items
- Anyone can view news

### 2. Request Validation Classes
- `app/Http/Requests/StoreNewsRequest.php` - Validation for creating news
- `app/Http/Requests/UpdateNewsRequest.php` - Validation for updating news

### 3. API Resources
- `app/Http/Resources/NewsResource.php` - Formats individual news responses
- `app/Http/Resources/NewsCollection.php` - Formats paginated news responses

### 4. NewsController
- Location: `app/Http/Controllers/Api/NewsController.php`
- Methods implemented:
  - `index` - List all news with filtering and pagination
  - `store` - Create a new news item
  - `show` - Get a specific news item
  - `update` - Update an existing news item
  - `destroy` - Delete a news item
  - `uploadImage` - Upload an image for a news item
  - `deleteImage` - Delete an image from a news item

### 5. Routes
- Added to `routes/api.php`:
  - `Route::apiResource('news', NewsController::class);`
  - `Route::post('/news/{news}/image', [NewsController::class, 'uploadImage']);`
  - `Route::delete('/news/{news}/image/{mediaId}', [NewsController::class, 'deleteImage']);`

### 6. Documentation
- Created `NEWS_API_DOCS.md` with comprehensive API documentation

## Features Implemented

### CRUD Operations
- Full Create, Read, Update, Delete functionality for news items

### Filtering and Search
- Filter by community_id
- Filter by author_id
- Search in title and content

### Sorting
- Sort by created_at, updated_at, published_at, or title
- Ascending or descending order

### Pagination
- Configurable items per page (default 15)

### Media Handling
- Support for uploading images with news items
- Integration with Spatie's Media Library
- Ability to delete specific images from news items

### Authorization
- Policy-based authorization ensuring users can only modify their own news

### Validation
- Comprehensive validation for all input data
- Custom error messages

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/news` | Get all news (paginated) |
| POST | `/api/news` | Create a new news item |
| GET | `/api/news/{id}` | Get a specific news item |
| PUT/PATCH | `/api/news/{id}` | Update a news item |
| DELETE | `/api/news/{id}` | Delete a news item |
| POST | `/api/news/{id}/image` | Upload an image for a news item |
| DELETE | `/api/news/{id}/image/{mediaId}` | Delete an image from a news item |

## Response Format

All responses follow a consistent JSON format with proper HTTP status codes. The API uses Laravel API Resources to ensure consistent response structure.

## Error Handling

The API properly handles:
- Validation errors (422)
- Authentication errors (401)
- Authorization errors (403)
- Not found errors (404)
- Server errors (500)

## Testing

The implementation includes feature tests in `tests/Feature/NewsApiTest.php` that cover all endpoints. These tests verify the functionality of the API but require a working database connection to run.