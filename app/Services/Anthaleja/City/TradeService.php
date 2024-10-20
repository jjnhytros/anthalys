<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\City\Resource;
use App\Models\Anthaleja\City\MapSquare;

class TradeService
{
    public function tradeResourcesBetweenSquares(MapSquare $fromSquare, MapSquare $toSquare, $resourceType, $quantity)
    {
        $fromResource = Resource::where('map_square_id', $fromSquare->id)->where('type', $resourceType)->first();
        $toResource = Resource::firstOrNew(['map_square_id' => $toSquare->id, 'type' => $resourceType]);

        if ($fromResource && $fromResource->amount >= $quantity) {
            // Calcola il costo del trasporto
            $transportCost = $this->calculateTransportCost($fromSquare, $toSquare, $quantity);

            // Simula il pagamento del trasporto (puoi implementare un sistema di pagamento tra quartieri)
            // Se c'è un sistema di bilancio per i quartieri, possiamo ridurre il budget qui
            // $toSquare->budget -= $transportCost;

            // Trasferisci risorse
            $fromResource->amount -= $quantity;
            $toResource->amount += $quantity;

            $fromResource->save();
            $toResource->save();

            // Log del commercio
            EventLog::create([
                'event_type' => 'resource_traded',
                'details' => json_encode([
                    'from_square' => $fromSquare->sector_name,
                    'to_square' => $toSquare->sector_name,
                    'resource_type' => $resourceType,
                    'quantity' => $quantity,
                    'transport_cost' => $transportCost,
                ]),
                'created_at' => now(),
            ]);

            return "Commercio di {$quantity} unità di {$resourceType} da {$fromSquare->sector_name} a {$toSquare->sector_name} completato. Costo del trasporto: {$transportCost}.";
        }

        return "Il rione {$fromSquare->sector_name} non ha abbastanza {$resourceType} per il commercio.";
    }

    public function calculateTransportCost(MapSquare $fromSquare, MapSquare $toSquare, $quantity)
    {
        // Calcola la distanza tra i due quartieri
        $distance = sqrt(pow($fromSquare->x_coordinate - $toSquare->x_coordinate, 2) + pow($fromSquare->y_coordinate - $toSquare->y_coordinate, 2));

        // Il costo del trasporto è proporzionale alla distanza e alla quantità
        return $distance * $quantity * 0.5;  // 0.5 è un fattore arbitrario che puoi modificare
    }
}
