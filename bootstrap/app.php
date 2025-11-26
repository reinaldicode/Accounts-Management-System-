<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',  // Comment dulu
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // AMS Middleware
            'ams.auth' => \App\Http\Middleware\CheckAMSSession::class,  // ← Untuk web session
            'ams.api.auth' => \App\Http\Middleware\AMSAuthentication::class,  // ← Untuk API JWT (nanti)
            'ams.system' => \App\Http\Middleware\CheckSystemAccess::class,
            'ams.api_key' => \App\Http\Middleware\ValidateSystemAPIKey::class,
            'ams.active' => \App\Http\Middleware\CheckUserActive::class,
            'ams.permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();