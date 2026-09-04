<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSitePasscode
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = SiteSetting::getSetting();

        // Gatekeeper is disabled or no passcode has been configured yet.
        if (!$setting->passcode_enabled || empty($setting->site_passcode)) {
            return $next($request);
        }

        // Authenticated admins bypass the site gatekeeper entirely.
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // The visitor has already unlocked the site during this session.
        if ($request->session()->get('site_passcode_verified')) {
            return $next($request);
        }

        // Never block the passcode entry / verification routes themselves.
        if ($request->routeIs('passcode.entry') || $request->routeIs('passcode.verify')) {
            return $next($request);
        }

        return redirect()->guest(route('passcode.entry'));
    }
}