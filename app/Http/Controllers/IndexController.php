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
        $events = Event::where('datetime', '<', now())->orderBy('datetime', 'desc')->take(10)->get();
        return $events;
    }

    public function passedEvent($id)
    {
        try {
            $id = Uuid::fromString($id);
        } catch (InvalidUuidStringException $e) {
            return Controller::redirectToErrorPage('Invalid event id');
        }
        //return passed event by id
        $event = Event::where('id', $id)->where('datetime', '<', now())->first();
        if (!$event) {
            return Controller::redirectToErrorPage('Event not found');
        }
        return view('event', ['event' => $event]);
    }
}
