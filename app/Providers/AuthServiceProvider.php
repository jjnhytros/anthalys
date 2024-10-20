<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\ConversationPolicy;
use App\Models\Anthaleja\SoNet\Conversation;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Conversation::class => ConversationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
