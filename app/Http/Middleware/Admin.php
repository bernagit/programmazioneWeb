<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && ($user->isAdmin() || $user->isSuperAdmin())) {
            return $next($request);
        } else {
            return redirect()->route('unauthorized');
        }
    }
}
