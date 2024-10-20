<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\Phone\Application;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::create(['icon' => 'bi bi-cash-stack', 'name' => 'Cash', 'link' => '#', 'status' => 'view']);
        Application::create(['icon' => 'bi bi-bank', 'name' => 'Bank', 'link' => "bank", 'status' => 'view']);
        Application::create(['icon' => 'bi bi-person', 'name' => 'Profile', 'link' => '#', 'status' => 'view']);
        Application::create(['icon' => 'bi bi-envelope', 'name' => 'Messages', 'link' => '#', 'status' => 'view']);
        Application::create(['icon' => 'bi bi-calendar', 'name' => 'Calendar', 'link' => '#', 'status' => 'hide']);
        // Application::create(['icon' => 'bi bi-chat', 'name' => 'SoNet', 'link' => route('sonet.chat.index'), 'status' => 'view']);
    }
}
