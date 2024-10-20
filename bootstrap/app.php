<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'))
                ->group(base_path('routes/api.php'))
                ->group(base_path('routes/ath.php'))
                ->group(base_path('routes/sonet.php'))
                ->group(base_path('routes/clair.php'))
                ->group(base_path('routes/wiki.php'));
            Route::middleware(['role:admin'])
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            'check.room.role' => App\Http\Middleware\SoNet\CheckRoomRole::class,
            // 'is_admin' => App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
