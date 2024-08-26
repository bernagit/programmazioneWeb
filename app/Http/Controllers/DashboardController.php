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
            $users = User::where('role', 'user')->get();
            $admins = User::where('role', 'admin')->get();
            // $requests = RequestModel::where('status', 'pending')->orderBy('created_at', 'desc')->get();
            // $pending_requests = $requests->count();
            $events = Event::all();
            $total_events = $events->count();
            $total_users = $users->count() + $admins->count();
            return view(
                'dashboard.super_admin',
                [
                    'authUser' => $user,
                    'users' => $users,
                    'admins' => $admins,
                    'events' => $events,
                    'totalUsers' => $total_users,
                    'totalEvents' => $total_events,
                    // 'pendingRequests' => $pending_requests
                ]
            );
        } elseif ($user->isAdmin()) {
            $users = User::where('role', 'user')->get();
            return view('dashboard.admin', ['events' => Event::all(), 'users' => $users]);
        } else {
            return view('dashboard.user', ['events' => Event::all()]);
        }
    }
}
