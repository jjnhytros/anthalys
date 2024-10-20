<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\Building;
use App\Models\Anthaleja\City\MapSquare;

class BuildingInteractionService
{
    public function interact(MapSquare $square)
    {
        $buildings = Building::where('map_square_id', $square->id)->get();

        foreach ($buildings as $building) {
            if ($building->type === 'commercial') {
                $square->socio_economic_status += 5;
            } elseif ($building->type === 'industrial') {
                $square->socio_economic_status -= 5;
            }

            // Impatto ambientale delle fabbriche
            if ($building->type === 'industrial') {
                $square->environmental_quality -= 10;
            }
        }

        $square->save();
    }

    public function interactWithBuilding(Character $character, Building $building)
    {
        switch ($building->type) {
            case 'bank':
                return $this->visitBank($character);
            case 'shop':
                return $this->visitShop($character);
            case 'hospital':
                return $this->visitHospital($character);
            case 'police_station':
                return $this->visitPoliceStation($character);
            case 'fire_station':
                return $this->visitFireStation($character);
            default:
                return "Nessuna interazione disponibile.";
        }
    }

    protected function visitBank(Character $character)
    {
        // Esempio di interazione con la banca: richiesta di prestito
        $bankService = new BankService();
        return $bankService->requestLoan($character, 1000);  // Il personaggio richiede un prestito di 1000 AA
    }

    protected function visitShop(Character $character)
    {
        // Logica per fare acquisti
        return "Il personaggio ha visitato il centro commerciale.";
    }

    protected function visitHospital(Character $character)
    {
        $attributes = $character->getAttributesField('health') ?? 50;

        // Verifica se il personaggio ha abbastanza soldi per le cure
        if ($character->cash >= 200) {
            // Sottrai il costo delle cure
            $character->cash -= 200;

            $currentHealth = $character->getAttributesField('health') ?? 50;  // Se non esiste, imposta un valore di default
            $character->setAttributesField('health', 100);
            $character->save();

            return "Il personaggio ha ricevuto cure mediche. Salute ripristinata al 100% con un costo di 200 AA.";
        } else {
            return "Il personaggio non ha abbastanza denaro per pagare le cure mediche.";
        }
    }

    protected function visitPoliceStation(Character $character)
    {
        // Logica per richiedere protezione dalla polizia
        if ($character->reputation < 30) {
            return "La polizia non ti fornirà protezione a causa della tua bassa reputazione.";
        } else {
            // Potresti aggiungere logiche come protezione contro attacchi o eventi futuri
            return "Hai richiesto protezione alla polizia.";
        }
    }

    protected function visitFireStation(Character $character)
    {
        // Logica per richiedere un'ispezione di sicurezza
        if ($character->cash >= 100) {
            // Sottrai il costo dell'ispezione
            $character->cash -= 100;
            $character->save();

            return "Ispezione di sicurezza completata. Costo: 100 AA.";
        } else {
            return "Il personaggio non ha abbastanza denaro per pagare l'ispezione.";
        }
    }
}
