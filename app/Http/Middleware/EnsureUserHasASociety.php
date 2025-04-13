<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasASociety
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()->societies()->exists())
            return to_route('societies.index')
                ->with([
                    'from' => $request->getUri()
                ])
                ->withErrors([
                    'message' => 'Vous devez créer une entreprise pour continuer'
                ]);
        return $next($request);
    }
}
