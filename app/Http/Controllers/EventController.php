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

    public function upload(Request $request)
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
        }

        $validated['created_by'] = auth()->id();
        // Create the event
        Event::create($validated);

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }
}
