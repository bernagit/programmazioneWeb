<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

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
}
