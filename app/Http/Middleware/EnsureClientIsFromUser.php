<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientIsFromUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, Client $client): Response
    {
        if ($client->user_id !== Auth::user()->id)
            return to_route('dashboard')->withErrors([
                'message' => 'Client introuvable.'
            ]);
        return $next($request);

    }
}
