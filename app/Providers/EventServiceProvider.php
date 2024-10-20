<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            'App\Listeners\LogUserLogin',
        ],
        Logout::class => [
            'App\Listeners\LogUserLogout',
        ],
    ];

    public function boot()
    {
        // parent::boot();
    }
}
