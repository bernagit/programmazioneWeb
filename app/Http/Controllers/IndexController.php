<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use \Ramsey\Uuid\Uuid as Uuid;
use \Ramsey\Uuid\Exception\InvalidUuidStringException;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function passedEvents()
    {
        //return last 10 passed events
        $events = Event::where('datetime', '<', now())->withCount('likes')->orderBy('likes_count', 'desc')->take(10)->get();
        return $events;
    }

    public function showEvent($id)
    {
        try {
            $id = Uuid::fromString($id);
        } catch (InvalidUuidStringException $e) {
            return Controller::redirectToErrorPage('Invalid event id');
        }
        $user = auth()->user();

        $event = Event::where('id', $id)
            ->when(!$user, function ($query) {
                return $query->where('datetime', '<', now());
            })
            ->first();

        if (!$event) {
            return Controller::redirectToErrorPage('Event not found');
        }
        return view('event', ['event' => $event]);
    }
}
