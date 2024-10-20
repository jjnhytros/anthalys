<?php

use App\Jobs\RandomEventJob;
use App\Models\Anthaleja\Character;
use App\Services\InteractionService;
use Illuminate\Support\Facades\Schedule;


// Schedule::command('monitor:neighborhoods')->daily();
Schedule::command('events:trigger-random')->daily();
Schedule::command('effects:process-long-term')->daily();
Schedule::command('logs:rotate')->monthly();
Schedule::command('events:trigger-personal')->daily();
Schedule::command('missions:assign')->daily();
Schedule::command('npc:manage-decisions')->daily();
Schedule::command('ecommerce:simulate-orders')->daily();
Schedule::job(new App\Jobs\Anthaleja\MegaWareHouse\UpdateWarehouseInventory)->daily();
Schedule::job(new App\Jobs\Anthaleja\MegaWareHouse\ResourceMaintenanceJob)->daily();

// Wiki Schedule
Schedule::command('ath:wikicontentmonitor')->daily();
Schedule::command('ath:moderate-content')->daily();
Schedule::command('knowledge:sync')->daily();
