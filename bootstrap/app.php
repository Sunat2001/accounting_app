<?php

use App\Http\Middleware\UpdateLastSeen;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: null,
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            UpdateLastSeen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withRouting(function (Router $router) {
        $router->group(['prefix' => 'api/v1'], function () use ($router) {
            require base_path('routes/api.php');
        });
    })->create();
