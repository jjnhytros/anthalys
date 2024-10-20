<?php

namespace App\Services\Anthaleja\City;

use Carbon\Carbon;
use App\Models\Anthaleja\EventLog;

class RotateOldEventLogsService
{
    public function rotateLogs($days = 96)
    {
        $cutoffDate = Carbon::now()->subDays($days);

        // Elimina tutti i log più vecchi di $days giorni
        EventLog::where('created_at', '<', $cutoffDate)->delete();

        return "Log più vecchi di {$days} giorni eliminati.";
    }
}
