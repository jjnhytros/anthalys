<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Wiki\Article;
use App\Models\Anthaleja\Wiki\Redirect;

class CleanRedirects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anthaleja:clean-wiki-redirects';
    protected $description = 'Clean Wiki Redirects';

    public function handle()
    {
        Redirect::whereNotIn('new_slug', Article::pluck('slug'))->delete();

        $this->info('Redirect obsoleti rimossi con successo.');
    }
}
