<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\PublicationCollection;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicationController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'publications'): string
    {
        $params = $request->only(['per_page', 'page', 'community_id', 'author_id']);

        // Sort the parameters to ensure consistent key generation
        ksort($params);

        // Create a unique key based on the parameters
        $keyString = $prefix . ':' . md5(serialize($params));

        return $keyString;
    }

    /**
     * Search publications by title or content.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (!$query) {
            return response()->json(['message' => 'Query parameter is required'], 400);
        }
        
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'publications_search');
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $publications = Cache::tags(['publications'])->remember($cacheKey, 60, function () use ($query, $request) {
                return Publication::where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->with(['community', 'author'])
                    ->paginate($request->get('per_page', 15));
            });
        } else {
            $publications = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return Publication::where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->with(['community', 'author'])
                    ->paginate($request->get('per_page', 15));
            });
        }
        
        return new PublicationCollection($publications);
    }
    /**
     * Display a listing of the publications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request);
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $publications = Cache::tags(['publications'])->remember($cacheKey, 60, function () use ($request) {
                $query = Publication::with(['community', 'author']);
                
                // Apply filters if provided
                if ($request->has('community_id')) {
                    $query->where('community_id', $request->community_id);
                }
                
                if ($request->has('author_id')) {
                    $query->where('user_id', $request->author_id);
                }
                
                return $query->paginate($request->get('per_page', 15));
            });
        } else {
            $publications = Cache::remember($cacheKey, 60, function () use ($request) {
                $query = Publication::with(['community', 'author']);
                
                // Apply filters if provided
                if ($request->has('community_id')) {
                    $query->where('community_id', $request->community_id);
                }
                
                if ($request->has('author_id')) {
                    $query->where('user_id', $request->author_id);
                }
                
                return $query->paginate($request->get('per_page', 15));
            });
        }
        
        return new PublicationCollection($publications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'community_id' => 'required|exists:communities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $publication = Publication::create($validated);

        // Load relationships for the response
        $publication->load(['community', 'author']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['publications'])->flush();
        }

        return new PublicationResource($publication);
    }

    /**
     * Display the specified publication.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        // Generate dynamic cache key
        $cacheKey = "publication_{$publication->id}";
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $publication = Cache::tags(['publications'])->remember($cacheKey, 60, function () use ($publication) {
                $publication->load(['community', 'author', 'media']);
                return $publication;
            });
        } else {
            $publication = Cache::remember($cacheKey, 60, function () use ($publication) {
                $publication->load(['community', 'author', 'media']);
                return $publication;
            });
        }
        
        return new PublicationResource($publication);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'community_id' => 'sometimes|required|exists:communities,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $publication->update($validated);

        // Load relationships for the response
        $publication->load(['community', 'author']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['publications'])->flush();
        }

        return new PublicationResource($publication);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        $publication->delete();

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['publications'])->flush();
        }

        return response()->noContent();
    }
}