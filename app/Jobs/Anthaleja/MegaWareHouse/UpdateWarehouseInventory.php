<?php

namespace App\Jobs\Anthaleja\MegaWareHouse;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Anthaleja\MegaWareHouse\WarehouseManagementService;

class UpdateWarehouseInventory implements ShouldQueue
{
    use Queueable;

    protected $warehouseService;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        // Richiedi il servizio WarehouseManagementService tramite il service container
        $warehouseService = app(WarehouseManagementService::class);

        // Esegui la logica di gestione dell'inventario
        $warehouseService->manageInventoryByCategory();
    }
}
