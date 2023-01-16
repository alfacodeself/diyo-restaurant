<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->key == null || $request->key != env('API_KEY') ) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => 'Unauthorize Access'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
