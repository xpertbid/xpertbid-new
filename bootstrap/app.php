<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Register custom middleware aliases
        $middleware->alias([
            'redirect.to.frontend' => \App\Http\Middleware\RedirectToFrontend::class,
        ]);
        
        // Override the default auth middleware
        $middleware->web(replace: [
            \Illuminate\Auth\Middleware\Authenticate::class => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
