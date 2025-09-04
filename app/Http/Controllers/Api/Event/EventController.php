<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $events = Event::with(['community', 'creator'])->paginate($perPage);
        
        return new EventCollection($events);
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
