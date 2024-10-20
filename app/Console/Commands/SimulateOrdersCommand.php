<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\Marketplace\Product;
use App\Services\Anthaleja\Marketplace\OrderService;

class SimulateOrdersCommand extends Command
{
    protected $signature = 'ecommerce:simulate-orders';
    protected $description = 'Simula gli ordini nel mini-ecommerce';

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function handle()
    {
        // Prende un personaggio casuale e un prodotto casuale
        $character = Character::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        // Simula l'acquisto di 1-3 unità di un prodotto
        $quantity = rand(1, 3);

        // Esegui l'ordine
        $result = $this->orderService->placeOrder($character, $product, $quantity);

        $this->info($result);
    }
}
