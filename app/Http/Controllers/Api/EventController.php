<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;

class EventController extends Controller
{
    //
    public function eventsList(Request $request)
    {
        // Base query with eager-loaded `user`
        $query = Event::with('user')->orderBy('start_date', 'desc');

        // Use pagination when page/per_page query params are present
        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 20);
            $events = $query->paginate($perPage);

            return EventResource::collection($events)->additional(['success' => true]);
        }

        // Otherwise return full collection
        $events = $query->get();
        return EventResource::collection($events)->additional(['success' => true]);
    }

    // Return a single event
    public function show(Event $event)
    {
        $event->load('user');
        return (new EventResource($event))->additional(['success' => true]);
    }

    // Create a new event
    public function createEvent(CreateEventRequest $request)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            $path = $file->store('events', 'public');
            $data['image'] = basename($path);
        }

        $user = $request->user();
        $data['added_by'] = $user->id ?? null;
        $data['added_at'] = now();

        $event = Event::create($data);

        return (new EventResource($event->load('user')))->additional(['success' => true])->response()->setStatusCode(201);
    }

    // Update an existing event
    public function updateEvent(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            if ($event->image && \Storage::disk('public')->exists('events/' . $event->image)) {
                \Storage::disk('public')->delete('events/' . $event->image);
            }
            $path = $file->store('events', 'public');
            $data['image'] = basename($path);
        }

        $event->update($data);

        return (new EventResource($event->fresh()->load('user')))->additional(['success' => true]);
    }

    // Delete (soft-delete) an event
    public function deleteEvent(Event $event)
    {
        $event->delete();
        return response()->json(['success' => true], 200);
    }
}
