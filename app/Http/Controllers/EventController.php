<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ImageRepository;
use App\Models\Event;

class EventController extends Controller
{
    private $imageRepository;
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    private function upload(Request $request)
    {
        $image = $request->file('image');
        $path = $this->imageRepository->uploadImage($image);
        //return response()->json(['path' => $path]);
        return $path;
    }
    public function index()
    {
        return view('event.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable|string',
            'datetime' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'additional_info' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $this->upload($request);
            if ($path === null) {
                return redirect()->back()->withErrors(['image' => 'Failed to upload the image. Please try again.']);
            } else {
                $validated['image_path'] = $path;
            }
        } else {
            $validated['image_path'] = $this->imageRepository->uploadNullImage();
        }

        $validated['created_by'] = auth()->id();
        // Create the event
        Event::create($validated);

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }

    public function edit(Request $request, $id)
    {
        $event = Event::find($id);
        return view('event.edit', ['event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable|string',
            'datetime' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'additional_info' => 'nullable|string',
        ]);

        $event = Event::find($id)->where('created_by', auth()->id())->first();

        if (!$event) {
            return redirect()->route('dashboard')->withErrors(['event' => 'You are not authorized to edit this event.']);
        }

        if ($request->hasFile('image')) {
            $path = $this->upload($request);
            if ($path === null) {
                return redirect()->back()->withErrors(['image' => 'Failed to upload the image. Please try again.']);
            } else {
                $validated['image_path'] = $path;
            }
        }

        $event->update($validated);

        return redirect()->route('dashboard')->with('success', 'Event updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::find($id)->where('created_by', auth()->id())->first();

        if (!$event) {
            return redirect()->route('dashboard')->withErrors(['event' => 'You are not authorized to delete this event.']);
        }

        $event->delete();
        return redirect()->route('dashboard')->with('success', 'Event deleted successfully!');
    }

    public function like($id)
    {
        try {
            $event = Event::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid event id'], 400);
        }
        // Check if the user has already liked the event
        if ($event->likes()->where('user_id', auth()->id())->exists()) {
            return response()->json([
                'message' => 'You have already liked this event.',
                'likes_count' => $event->likesCount()
            ], 400);
        }

        // Create the like
        $event->likes()->create(['user_id' => auth()->id()]);

        return response()->json([
            'success' => 'Liked the event!',
            'likes_count' => $event->likesCount(),
            'liked' => $event->isLikedByUser()
        ]);
    }

    public function unlike($id)
    {
        try {
            $event = Event::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid event id'], 400);
        }
        // Check if the like exists before trying to delete
        $like = $event->likes()->where('user_id', auth()->id())->first();

        if (!$like) {
            return response()->json([
                'message' => 'You have not liked this event yet.',
                'likes_count' => $event->likesCount()
            ], 400);
        }

        // Delete the like
        $like->delete();

        return response()->json([
            'success' => 'Unliked the event!',
            'likes_count' => $event->likesCount(),
            'liked' => $event->isLikedByUser()
        ]);
    }
}
