<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class TimelineAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $var_start_date, $var_end_date): Response
    {
        $now = Carbon::now();
        $startDate = notification_setting($var_start_date)?->value ;
        $endDate = notification_setting($var_end_date)?->value;

        if ($now->between(Carbon::parse($startDate), Carbon::parse($endDate))) {
            return $next($request);
        }

        abort(404);
    }
}
