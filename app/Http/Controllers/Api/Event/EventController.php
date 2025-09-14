<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    /**
     * Generate a cache key based on request parameters
     */
    protected function generateCacheKey(Request $request, string $prefix = 'events'): string
    {
        $params = $request->only(['per_page', 'page', 'query', 'status', 'community_id']);

        // Sort the parameters to ensure consistent key generation
        ksort($params);

        // Create a unique key based on the parameters
        $keyString = $prefix . ':' . md5(serialize($params));

        return $keyString;
    }

    /**
     * Search events by title, description, or location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (!$query) {
            return response()->json(['message' => 'Query parameter is required'], 400);
        }
        
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request, 'events_search');
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $events = Cache::tags(['events'])->remember($cacheKey, 60, function () use ($query, $request) {
                return Event::where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->with(['community', 'creator'])
                    ->paginate($request->get('per_page', 15));
            });
        } else {
            $events = Cache::remember($cacheKey, 60, function () use ($query, $request) {
                return Event::where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->with(['community', 'creator'])
                    ->paginate($request->get('per_page', 15));
            });
        }
        
        return new EventCollection($events);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Generate dynamic cache key
        $cacheKey = $this->generateCacheKey($request);
        
        // Use cache tags if supported by the cache driver
        if (method_exists(Cache::getStore(), 'tags')) {
            $events = Cache::tags(['events'])->remember($cacheKey, 60, function () use ($request) {
                $perPage = $request->input('per_page', 15);
                return Event::with(['community', 'creator'])->paginate($perPage);
            });
        } else {
            $events = Cache::remember($cacheKey, 60, function () use ($request) {
                $perPage = $request->input('per_page', 15);
                return Event::with(['community', 'creator'])->paginate($perPage);
            });
        }
        
        return new EventCollection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'community_id' => 'required|exists:communities,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|string|in:draft,published,cancelled',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        $event = Event::create($validated);

        // Load relationships for the response
        $event->load(['community', 'creator']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'start_at' => 'sometimes|required|date',
            'end_at' => 'sometimes|required|date|after:start_at',
            'community_id' => 'sometimes|required|exists:communities,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'status' => 'nullable|string|in:draft,published,cancelled',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        $event->update($validated);

        // Load relationships for the response
        $event->load(['community', 'creator']);

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        // Invalidate cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Load relationships for the detail view
        $event->load(['community', 'creator', 'attendees']);
        
        return new EventResource($event);
    }
}
