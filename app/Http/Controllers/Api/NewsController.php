<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'news'): string
    {
        $params = $request->only([
            'community_id', 'author_id', 'search', 'sort_by', 'sort_direction', 'per_page', 'page'
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
        info(print_r($request->all(), true));
        $query = News::with(['author', 'community', 'media']);

        // Apply filters
        if ($request->has('community_id')) {
            $query->where('community_id', $request->community_id);
        }

        if ($request->has('author_id')) {
            $query->where('user_id', $request->author_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
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
            $news = Cache::tags(['news'])->remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        } else {
            $news = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return $query->paginate($request->get('per_page', 15));
            });
        }

        return new NewsCollection($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $news = News::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $news->addMedia($image)->toMediaCollection('images');
            }
        }

        // Load relationships for the response
        $news->load(['author', 'community', 'media']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['news'])->flush();
        }

        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $news->load(['author', 'community', 'media']);
        return new NewsResource($news);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        // Check policy
        $this->authorize('update', $news);

        $data = $request->validated();

        $news->update($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Clear existing images if needed, or add to existing collection
            foreach ($request->file('images') as $image) {
                $news->addMedia($image)->toMediaCollection('images');
            }
        }

        // Load relationships for the response
        $news->load(['author', 'community', 'media']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['news'])->flush();
        }

        return new NewsResource($news);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Check policy
        $this->authorize('delete', $news);

        $news->delete();

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['news'])->flush();
        }

        return response()->noContent();
    }
    
    /**
     * Upload an image for a news item.
     */
    public function uploadImage(Request $request, News $news): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $this->authorize('update', $news);

        $media = $news->addMediaFromRequest('image')->toMediaCollection('images');

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image_url' => $media->getUrl(),
        ]);
    }

    /**
     * Delete an image from a news item.
     */
    public function deleteImage(News $news, int $mediaId): JsonResponse
    {
        $this->authorize('update', $news);

        $media = $news->getMedia('images')->firstWhere('id', $mediaId);

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $media->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}