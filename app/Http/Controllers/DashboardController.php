<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;
use App\Models\Event;
use App\Models\User;

class DashboardController extends Controller
{
    public function handle()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $requests = RequestModel::where('status', 'pending')->orderBy('created_at', 'desc')->get();
            $pending_requests = $requests->count();
            $events = Event::all();
            $total_events = $events->count();
            $total_users = User::where('role', 'user')->count();
            return view(
                'dashboard.super_admin',
                [
                    'requests' => $requests,
                    'events' => $events,
                    'totalUsers' => $total_users,
                    'totalEvents' => $total_events,
                    'pendingRequests' => $pending_requests
                ]
            );
        } elseif ($user->isAdmin()) {
            return view('dashboard.admin');
        } else {
            $events = Event::all();
            return view('dashboard.user', ['events' => $events]);
        }
    }
}
