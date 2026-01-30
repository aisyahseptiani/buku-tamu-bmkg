<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckScan; // ⬅️ WAJIB ADA DI SINI

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ⬇️⬇️⬇️ TARUH DI SINI, JANGAN DI TEMPAT LAIN ⬇️⬇️⬇️
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminAuth::class,
            'check.scan' => CheckScan::class,
        ]);
    })
    // ⬆️⬆️⬆️ SAMPAI SINI ⬆️⬆️⬆️

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
