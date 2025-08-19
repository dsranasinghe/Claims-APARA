<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBankAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    // For routes with application_no
    if ($request->route('application')) {
        $application = Application::findOrFail($request->route('application'));
        if ($application->bank_id != session('bank_id')) {
            abort(403, 'Unauthorized access to this application');
        }
    }

    // For routes with claim ID
    if ($request->route('id')) {
        $claim = OverdueClaim::findOrFail($request->route('id'));
        if ($claim->application->bank_id != session('bank_id')) {
            abort(403, 'Unauthorized access to this claim');
        }
    }

    return $next($request);
}
}
