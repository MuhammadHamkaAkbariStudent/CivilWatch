<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login, atau role-nya tidak cocok dengan yang diizinkan
        if (! $request->user() || $request->user()->role !== $role) {
            
            // Lemparkan kembali sesuai habitat aslinya
            if ($request->user() && $request->user()->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            
            return redirect('/feed');
        }

        // Jika role cocok, persilakan lewat
        return $next($request);
    }
}