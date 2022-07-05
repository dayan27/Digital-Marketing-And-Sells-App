<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
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
         if ($request->user()->type != 'agent') {
            return response()->json('un authorized user',401);
          }
        return $next($request);
    }
}
