# Post API Implementation Summary

This document provides a summary of the newly implemented Post API module for the community app, designed for integration with Flutter applications.

## Overview

The Post API module allows users to create, read, update, and delete posts with optional image attachments. It follows the same patterns and conventions as the existing News API module, ensuring consistency across the application.

## Key Features

1. **CRUD Operations**: Full Create, Read, Update, and Delete functionality for posts
2. **Image Management**: Support for uploading and deleting images associated with posts
3. **Filtering and Sorting**: Query parameters for filtering posts by community, author, type, and search terms
4. **Pagination**: Built-in pagination support for efficient data retrieval
5. **Authorization**: Policy-based access control for update and delete operations
6. **Caching**: Cache tags for efficient cache invalidation

## API Endpoints

All endpoints are protected and require authentication via Laravel Sanctum.

### Main Endpoints
- `GET /api/posts` - List all posts with filtering and pagination
- `POST /api/posts` - Create a new post
- `GET /api/posts/{id}` - Get a specific post
- `PUT/PATCH /api/posts/{id}` - Update a post
- `DELETE /api/posts/{id}` - Delete a post

### Image Management Endpoints
- `POST /api/posts/{id}/image` - Upload an image to a post
- `DELETE /api/posts/{id}/image/{mediaId}` - Delete an image from a post

### Like Management Endpoints
- `POST /api/posts/{id}/like` - Like a post
- `DELETE /api/posts/{id}/like` - Unlike a post

## Data Structure

Posts contain the following fields:
- `id` - Unique identifier
- `title` - Post title (required)
- `body` - Post content (required)
- `type` - Post type (default: 'news')
- `published_at` - Publication date (nullable)
- `is_pinned` - Whether the post is pinned (boolean)
- `community_id` - Associated community (required)
- `user_id` - Author (set automatically from authenticated user)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### Like Information
Posts also include like information in their responses:
- `like_count` - Number of likes the post has received
- `user_has_liked` - Boolean indicating if the authenticated user has liked the post

## Image Handling

Posts support up to 5 images per post. Images are handled through the Spatie Media Library package:
- Images are stored in the 'images' media collection
- Supported formats: JPEG, PNG, JPG, GIF, WEBP
- Maximum file size: 2MB per image
- Images can be uploaded during post creation or added later

## Validation

The API includes comprehensive validation:
- Title is required and limited to 255 characters
- Body is required
- Community ID must reference an existing community
- Image files must be valid image formats within size limits
- Date fields must contain valid dates

## Flutter Integration

For Flutter applications, the following integration patterns are recommended:

### Creating a Post with Image
1. Authenticate user and obtain API token
2. Prepare multipart request with post data and image file
3. Send POST request to `/api/posts`
4. Handle response with created post data including image URLs

### Listing Posts
1. Send GET request to `/api/posts` with optional query parameters
2. Parse paginated response
3. Display posts with associated images

### Updating a Post
1. Send PUT/PATCH request to `/api/posts/{id}` with updated data
2. Handle response with updated post data

### Deleting a Post
1. Send DELETE request to `/api/posts/{id}`
2. Handle 204 No Content response

### Uploading Additional Images
1. Prepare multipart request with image file
2. Send POST request to `/api/posts/{id}/image`
3. Handle response with image URL

### Liking/Unliking a Post
1. Send POST request to `/api/posts/{id}/like` to like a post
2. Send DELETE request to `/api/posts/{id}/like` to unlike a post
3. Handle response with updated like count

## Error Handling

The API follows standard HTTP status codes:
- 200 - Success
- 201 - Created
- 204 - No Content (successful delete)
- 400 - Bad Request (validation errors)
- 401 - Unauthorized
- 403 - Forbidden
- 404 - Not Found
- 422 - Unprocessable Entity (validation errors)
- 500 - Internal Server Error

Validation errors are returned in a consistent format with detailed error messages for each field.

## Performance Considerations

- Results are cached using cache tags for efficient invalidation
- Database queries include appropriate indexes for filtering
- Image URLs are generated on-demand for optimal delivery
- Pagination limits prevent excessive data transfer

## Security

- All endpoints require authentication
- Authorization policies control access to update/delete operations
- File uploads are validated for type and size
- SQL injection prevention through Laravel's query builder
- XSS prevention through proper data escaping in responses

## Future Enhancements

Potential future enhancements could include:
- Comment functionality for posts
- Post sharing capabilities
- Rich text formatting support
- Video attachment support
- Tagging system for posts