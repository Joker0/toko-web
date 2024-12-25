<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class APIAuthMiddleware
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
        $token = $request->header('Authorization');
        $auth = true;

        if (!$token) {
            $auth = false;
            
        }
        $user = User::where('token', $token)->first();

        if (!$user) {
            $auth = false;
        }

        Auth::login($user);

        if ($auth) {
            return $next($request);
        } else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
