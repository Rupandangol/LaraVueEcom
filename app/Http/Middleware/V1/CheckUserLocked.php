<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('user')->user();
        if ($user && $user->lock === 1) {
            return response()->json([
                'status' => 'failed',
                'message' => 'You account is locked. Pls contact support!!',
            ], 403);
        }

        return $next($request);
    }
}
