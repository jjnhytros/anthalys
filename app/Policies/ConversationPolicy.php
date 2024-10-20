<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Anthaleja\SoNet\Conversation;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->participants->contains($user->character->id);
    }

    public function sendMessage(User $user, Conversation $conversation)
    {
        return $conversation->participants->contains($user->character->id);
    }
}
