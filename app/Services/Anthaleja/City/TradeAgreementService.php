<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\TradeAgreement;

class TradeAgreementService
{
    public function createTradeAgreement(MapSquare $fromSquare, MapSquare $toSquare, $resourceType, $quantity, $duration)
    {
        return TradeAgreement::create([
            'from_square_id' => $fromSquare->id,
            'to_square_id' => $toSquare->id,
            'resource_type' => $resourceType,
            'quantity' => $quantity,
            'duration' => $duration,  // Durata dell'accordo in giorni o mesi
            'status' => 'active',
        ]);
    }

    public function processTradeAgreements()
    {
        $activeAgreements = TradeAgreement::where('status', 'active')->get();

        foreach ($activeAgreements as $agreement) {
            // Recupera i quartieri coinvolti nell'accordo
            $fromSquare = MapSquare::find($agreement->from_square_id);
            $toSquare = MapSquare::find($agreement->to_square_id);

            // Effettua il commercio regolare
            $tradeService = new TradeService();
            $tradeService->tradeResourcesBetweenSquares($fromSquare, $toSquare, $agreement->resource_type, $agreement->quantity);

            // Riduci la durata dell'accordo
            $agreement->duration -= 1;

            if ($agreement->duration <= 0) {
                $agreement->status = 'completed';
            }

            $agreement->save();
        }
    }
}
