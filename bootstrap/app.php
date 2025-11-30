<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'check.company.status' => \App\Http\Middleware\CheckCompanyStatus::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/callback/xendit', // Sesuaikan dengan URL route callback Anda
            'callback/xendit',     // Tambahkan variasi tanpa 'api' untuk berjaga-jaga
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
