<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        $user = $event->user;
        $character = $user->character;
        if ($character) {
            $character->update(['is_online' => false]);
        }
    }
}
