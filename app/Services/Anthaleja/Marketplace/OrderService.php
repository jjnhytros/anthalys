<?php

namespace App\Services\Anthaleja\Marketplace;

use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\Marketplace\Order;
use App\Models\Anthaleja\Marketplace\Product;

class OrderService
{
    public function placeOrder(Character $character, Product $product, $quantity)
    {
        // Verifica se la quantità richiesta è disponibile
        if ($product->quantity < $quantity) {
            return "Quantità insufficiente per il prodotto {$product->name}. Disponibile: {$product->quantity}.";
        }

        // Calcolo del prezzo totale
        $totalPrice = $product->price * $quantity;

        // Verifica se il personaggio ha abbastanza denaro
        if ($character->cash < $totalPrice) {
            return "Fondi insufficienti per completare l'ordine. Il totale è {$totalPrice} AA.";
        }

        // Esegui la transazione in una singola operazione atomica
        DB::transaction(function () use ($character, $product, $quantity, $totalPrice) {
            // Riduci la quantità del prodotto
            $product->quantity -= $quantity;
            $product->save();

            // Riduci il denaro del personaggio
            $character->cash -= $totalPrice;
            $character->save();

            // Crea l'ordine
            Order::create([
                'character_id' => $character->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'completed',
            ]);
        });

        return "Ordine completato con successo per {$quantity} {$product->name}(s). Totale: {$totalPrice} AA.";
    }
}
