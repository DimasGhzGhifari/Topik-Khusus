<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // Baris api tadi sudah saya hapus agar tidak error
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Ini untuk mematikan CSRF pada /publish agar bisa di-test di Postman
        $middleware->validateCsrfTokens(except: [
            '/publish', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();