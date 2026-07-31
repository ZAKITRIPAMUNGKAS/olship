<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdminIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && !$user->is_active) {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda dinonaktifkan oleh sistem.'], 403);
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda dinonaktifkan oleh sistem.']);
        }
        return $next($request);
    }
}
