<?php

namespace App\Jobs\Anthaleja\MegaWareHouse;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Anthaleja\MegaWareHouse\WarehouseManagementService;

class ResourceMaintenanceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $warehouseService = app(WarehouseManagementService::class);
        $warehouseService->checkResourceLevels();
    }
}
