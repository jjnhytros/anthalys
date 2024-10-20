<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use Illuminate\Support\Facades\DB;

class ArchiveOldEventLogsService
{
    public function archiveOldLogs($days = 30)
    {
        $cutoffDate = now()->subDays($days);

        // Recupera tutti i log più vecchi di $days giorni
        $oldLogs = EventLog::where('created_at', '<', $cutoffDate)->get();

        // Sposta i log vecchi nella tabella di archivio
        foreach ($oldLogs as $log) {
            DB::table('event_logs_archive')->insert($log->toArray());
            $log->delete();  // Rimuovi il log dalla tabella originale
        }

        return count($oldLogs) . " log spostati nell'archivio.";
    }
}
