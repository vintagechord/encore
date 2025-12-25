<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminGate
{
    /**
     * Simple session-based gate for admin pages.
     * Redirects to unlock form when not unlocked.
     */
    public function handle(Request $request, Closure $next)
    {
        // Already unlocked for this session
        if ($request->session()->get('admin_unlocked') === true) {
            return $next($request);
        }

        // Allow preflight/HEAD quietly
        if ($request->isMethod('OPTIONS') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        // Send to unlock page with intended redirect
        $intended = $request->fullUrl();
        return redirect()->route('admin.unlock', ['return' => $intended]);
    }
}

