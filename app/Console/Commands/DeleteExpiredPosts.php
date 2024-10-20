<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\SoNet\SonetPost;

class DeleteExpiredPosts extends Command
{
    protected $signature = 'anthaleja:delete-expired-posts';
    protected $description = 'Elimina i post scaduti';

    public function handle()
    {
        $expiredPosts = SonetPost::where('expires_at', '<', now())->get();

        foreach ($expiredPosts as $post) {
            $post->delete();
        }

        $this->info('I post scaduti sono stati eliminati.');
    }
}
