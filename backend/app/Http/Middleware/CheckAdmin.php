<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Admin access only.'
            ], 403);
        }

        return $next($request);
    }
}