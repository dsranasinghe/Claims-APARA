<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreSlecicSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has(['username', 'employee_id', 'department'])) {
            session([
                'username'    => $request->username,
                'employee_id' => $request->employee_id,
                'department'  => $request->department,
            ]);
        }

        return $next($request);
    }
}
