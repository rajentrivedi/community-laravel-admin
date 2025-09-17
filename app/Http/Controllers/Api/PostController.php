<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'posts'): string
    {
        $params = $request->only([
            'community_id', 'author_id', 'search', 'sort_by', 'sort_direction', 'per_page', 'page', 'type'
        ]);

        // Sort the parameters to ensure consistent key generation
        ksort($params);

        // Create a unique key based on the parameters
        $keyString = $prefix . ':' . md5(serialize($params));

        return $keyString;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['author', 'community', 'media']);

        // Apply filters
        if ($request->has('community_id')) {
            $query->where('community_id', $request->community_id);
        }

        if ($request->has('author_id')) {
            $query->where('user_id', $request->author_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        // Only allow sorting by specific columns
        $allowedSortColumns = ['created_at', 'updated_at', 'published_at', 'title'];
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request);
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $posts = Cache::tags(['posts'])->remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        } else {
            $posts = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        }

        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $post->addMedia($image)->toMediaCollection('images');
            }
        }

        // Load relationships for the response
        $post->load(['author', 'community', 'media']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['posts'])->flush();
        }

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['author', 'community', 'media']);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Check policy
        $this->authorize('update', $post);

        $data = $request->validated();

        $post->update($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Add new images to existing collection
            foreach ($request->file('images') as $image) {
                $post->addMedia($image)->toMediaCollection('images');
            }
        }

        // Load relationships for the response
        $post->load(['author', 'community', 'media']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['posts'])->flush();
        }

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check policy
        $this->authorize('delete', $post);

        $post->delete();

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['posts'])->flush();
        }

        return response()->noContent();
    }
    
    /**
     * Upload an image for a post.
     */
    public function uploadImage(Request $request, Post $post): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $this->authorize('update', $post);

        $media = $post->addMediaFromRequest('image')->toMediaCollection('images');

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image_url' => $media->getUrl(),
        ]);
    }

    /**
     * Delete an image from a post.
     */
    public function deleteImage(Post $post, int $mediaId): JsonResponse
    {
        $this->authorize('update', $post);

        $media = $post->getMedia('images')->firstWhere('id', $mediaId);

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $media->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
    
    /**
     * Like a post.
     */
    public function like(Post $post, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Check if the user has already liked the post
        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        
        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this post'], 409);
        }
        
        // Create the like
        $like = new Like([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        $post->likes()->save($like);
        
        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['posts'])->flush();
        }
        
        return response()->json([
            'message' => 'Post liked successfully',
            'like_count' => $post->likes()->count(),
        ], 201);
    }
    
    /**
     * Unlike a post.
     */
    public function unlike(Post $post, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Check if the user has liked the post
        $like = $post->likes()->where('user_id', $user->id)->first();
        
        if (!$like) {
            return response()->json(['message' => 'You have not liked this post'], 404);
        }
        
        // Delete the like
        $like->delete();
        
        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['posts'])->flush();
        }
        
        return response()->json([
            'message' => 'Post unliked successfully',
            'like_count' => $post->likes()->count(),
        ]);
    }
}