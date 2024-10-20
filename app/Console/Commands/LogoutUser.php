<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class LogoutUser extends Command
{
    // Nome del comando
    protected $signature = 'user:logout';

    // Descrizione del comando
    protected $description = 'Log out the current authenticated user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (Auth::check()) {
            session()->put('logged_out', true);
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            $this->info('User logged out successfully.');
        } else {
            $this->warn('No user is currently logged in.');
        }

        return 0;
    }
}
