<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Tambahkan ini di atas

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Daftarkan alias untuk RoleMiddleware yang baru kita buat
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. Modifikasi redirect bawaan jika user yang sudah login mencoba buka /login lagi
        $middleware->redirectUsersTo(fn (Request $request) =>
            $request->user()->role === 'admin' ? '/admin/dashboard' : '/feed'
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();