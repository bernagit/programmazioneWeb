<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;
use App\Models\Event;
use App\Models\User;
use App\Models\Like as Likes;

class DashboardController extends Controller
{
    public function handle()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $users = User::where('role', 'user')->get();
            $admins = User::where('role', 'admin')->get();

            // get all events with their creator
            $events = Event::with('creator')->get();

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln($events);
            // $events = Event::all();
            $total_events = $events->count();
            $total_users = $users->count() + $admins->count();
            $total_likes = Likes::all()->count();
            return view(
                'dashboard.super_admin',
                [
                    'authUser' => $user,
                    'users' => $users,
                    'admins' => $admins,
                    'events' => $events,
                    'totalUsers' => $total_users,
                    'totalEvents' => $total_events,
                    'totalLikes' => $total_likes,
                    // 'pendingRequests' => $pending_requests
                ]
            );
        } elseif ($user->isAdmin()) {
            $users = User::where('role', 'user')->get();
            return view(
                'dashboard.admin',
                [
                    'events' => Event::all(),
                    'users' => $users
                ]
            );
        } else {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            return view(
                'dashboard.user',
                [
                    'events' => Event::all(),
                    'latitude' => $user->prefLatitude,
                    'longitude' => $user->prefLongitude,
                    'price' => $user->prefPrice,
                    'radius' => $user->prefRadius,
                ]
            );
        }
    }

    public function updateUserSettings(Request $request)
    {
        $user = Auth::user();
        $user->update($request->all());
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
