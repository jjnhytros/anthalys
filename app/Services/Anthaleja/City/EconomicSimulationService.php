<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\EventLog;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\City\Property;
use App\Models\Anthaleja\City\Resource;
use App\Models\Anthaleja\City\MapSquare;;

use App\Models\Anthaleja\City\Investment;

class EconomicSimulationService
{
    protected $machineLearningService;
    public function __construct(MachineLearningService $machineLearningService)
    {
        $this->machineLearningService = $machineLearningService;
    }

    public function simulateEconomy(MapSquare $square)
    {
        // Usa le previsioni del machine learning per influenzare la simulazione
        $predictedGrowth = $this->machineLearningService->predictEconomicTrends($square);

        // Simula l'economia del quartiere in base agli eventi e alle interazioni
        $economicGrowth = rand(-10, 20) + $predictedGrowth;  // Somma la previsione alla crescita simulata

        $square->socio_economic_status = max(0, min(100, $square->socio_economic_status + $economicGrowth));
        $square->save();

        return "L'economia nel settore {$square->sector_name} è cambiata di {$economicGrowth} punti.";
    }

    public function grantLoan(Character $character, $amount, $interestRate)
    {
        // Aggiungi il prestito all'equilibrio del personaggio
        $character->cash += $amount;
        $character->loan_amount = $amount;
        $character->loan_interest = $interestRate;
        $character->loan_due_date = now()->addMonths(6); // Scadenza dopo 6 mesi
        $character->save();

        // Registra l'evento nel log
        EventLog::logLoanEvent($character, $amount, $interestRate);

        return "Prestito di {$amount} AA con tasso di interesse del {$interestRate}% concesso a {$character->username}.";
    }

    public function repayLoan(Character $character, $amount)
    {
        // Verifica se l'importo è sufficiente a coprire il debito
        if ($amount < $character->loan_amount) {
            return "Il pagamento non è sufficiente a coprire il prestito.";
        }

        // Paga il prestito
        $character->cash -= $amount;
        $character->loan_amount = 0;
        $character->loan_interest = 0;
        $character->loan_due_date = null;
        $character->save();

        // Registra l'evento nel log
        EventLog::logLoanRepaymentEvent($character, $amount);

        return "{$character->username} ha rimborsato il prestito di {$amount} AA.";
    }

    public function investInBusiness(Character $character, $investmentAmount, $riskLevel)
    {
        // Simula il ritorno dell'investimento basato sul rischio
        if ($riskLevel == 'alto') {
            $return = rand(-$investmentAmount, $investmentAmount * 3);  // Alto rischio, potenziale alto ritorno o perdita
        } elseif ($riskLevel == 'medio') {
            $return = rand(-$investmentAmount, $investmentAmount * 2);
        } else {
            $return = rand(0, $investmentAmount * 1.5);  // Basso rischio, minori perdite o guadagni
        }

        $character->cash += $return;
        $character->save();

        EventLog::logInvestmentEvent($character, $investmentAmount, $return, $riskLevel);

        return "Investimento di {$investmentAmount} AA con rischio {$riskLevel}: ritorno di {$return} AA.";
    }

    public function collectTaxes(Character $character, $taxRate)
    {
        // Calcola l'importo della tassa
        $taxAmount = $character->cash * ($taxRate / 100);

        // Verifica se il personaggio ha abbastanza denaro
        if ($character->cash < $taxAmount) {
            return "{$character->username} non ha abbastanza denaro per pagare le tasse.";
        }

        // Riduci il denaro del personaggio
        $character->cash -= $taxAmount;
        $character->save();

        EventLog::logTaxEvent($character, $taxAmount);

        return "{$character->username} ha pagato {$taxAmount} AA di tasse.";
    }

    public function buyProperty(Character $buyer, $propertyId)
    {
        $property = Property::find($propertyId);

        if ($buyer->cash < $property->price) {
            return "Acquisto fallito: {$buyer->username} non ha abbastanza denaro.";
        }

        // Aggiorna il proprietario e riduci i soldi del compratore
        $buyer->cash -= $property->price;
        $buyer->save();

        $property->owner_id = $buyer->id;
        $property->save();

        EventLog::logPropertyTransaction($buyer, $property, 'acquisto');

        return "{$buyer->username} ha acquistato la proprietà per {$property->price} AA.";
    }

    public function fluctuateMarketPrices(MapSquare $square)
    {
        // Simula una fluttuazione del prezzo su scala rionale
        $fluctuation = rand(-20, 20);  // Fluttuazione tra -20% e +20% per tutte le proprietà nel rione

        // Aggiorna il prezzo delle proprietà residenziali e commerciali
        foreach ($square->properties as $property) {
            $newPrice = $property->price + ($property->price * ($fluctuation / 100));

            // Limita il prezzo a non meno del 50% del valore originale
            $property->price = max($newPrice, $property->price * 0.5);
            $property->save();
        }

        EventLog::logMarketFluctuation($square, $fluctuation);
    }

    public function buyResource(Character $character, $resourceType, $quantity)
    {
        // Recupera la risorsa esistente del personaggio
        $resource = Resource::firstOrNew(['character_id' => $character->id, 'type' => $resourceType]);

        // Controlla se il personaggio può immagazzinare la risorsa
        if ($resource->max_amount > 0 && ($resource->amount + $quantity) > $resource->max_amount) {
            return "{$character->username} non può immagazzinare più di {$resource->max_amount} unità di {$resourceType}.";
        }

        // Il prezzo dipende dalla disponibilità della risorsa nel rione (logica simulata per disponibilità)
        $square = $character->mapSquare;
        $availability = $this->getResourceAvailability($square, $resourceType);
        $pricePerUnit = rand(10, 50) - $availability;
        $totalPrice = max(1, $pricePerUnit * $quantity);

        // Verifica se il personaggio ha abbastanza denaro
        if ($character->cash < $totalPrice) {
            return "{$character->username} non ha abbastanza denaro per acquistare {$quantity} unità di {$resourceType}.";
        }

        // Riduci il denaro del personaggio e aggiorna la quantità di risorsa
        $character->cash -= $totalPrice;
        $character->save();

        $resource = Resource::firstOrNew(['character_id' => $character->id, 'type' => $resourceType]);
        $resource->amount += $quantity;
        $resource->save();

        // Log della transazione
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'resource_purchased',
            'details' => json_encode([
                'resource_type' => $resourceType,
                'quantity' => $quantity,
                'price' => $totalPrice
            ]),
            'event_context' => json_encode(['city_state' => 'market_transaction']),
            'created_at' => now(),
        ]);

        return "{$character->username} ha acquistato {$quantity} unità di {$resourceType} per {$totalPrice} AA.";
    }

    public function consumeResource(Character $character, $resourceType, $quantity)
    {
        // Recupera la risorsa
        $resource = Resource::where('character_id', $character->id)->where('type', $resourceType)->first();

        // Verifica se la risorsa esiste e se c'è abbastanza quantità
        if (!$resource || $resource->amount < $quantity) {
            return "{$character->username} non ha abbastanza {$resourceType}.";
        }

        // Riduce la quantità di risorsa
        $resource->amount -= $quantity;
        $resource->save();

        // Log del consumo
        EventLog::logResourceConsumption($character, $resource, $quantity);

        return "{$character->username} ha consumato {$quantity} unità di {$resourceType}.";
    }

    public function sellResource(Character $character, $resourceType, $quantity)
    {
        $resource = Resource::where('character_id', $character->id)
            ->where('type', $resourceType)
            ->first();

        if (!$resource || $resource->amount < $quantity) {
            return "{$character->username} non ha abbastanza {$resourceType} per vendere.";
        }

        // Prezzo di vendita ridotto rispetto all'acquisto
        $priceService = new ResourcePriceService();
        $pricePerUnit = $priceService->calculateResourcePrice($resourceType, $character->mapSquare) * 0.8;
        $totalPrice = $pricePerUnit * $quantity;

        // Riduci la quantità di risorsa e aumenta il denaro del personaggio
        $resource->amount -= $quantity;
        $resource->save();

        $character->cash += $totalPrice;
        $character->save();

        // Log della vendita
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'resource_sold',
            'details' => json_encode([
                'resource_type' => $resourceType,
                'quantity' => $quantity,
                'price' => $totalPrice
            ]),
            'event_context' => json_encode(['city_state' => 'market_transaction']),
            'created_at' => now(),
        ]);

        return "{$character->username} ha venduto {$quantity} unità di {$resourceType} per {$totalPrice} AA.";
    }

    public function handleResourceEvent($eventType, MapSquare $square)
    {
        // Crea un'istanza del servizio ResourcePriceService
        $priceService = new ResourcePriceService();

        // Definizione delle risorse che possono essere influenzate
        $resourcesAffected = [
            'cibo' => rand(10, 50), // Aumento o diminuzione casuale in percentuale
            'acqua' => rand(5, 20),
            'carburante' => rand(20, 100)
        ];

        foreach ($resourcesAffected as $resourceType => $percentageChange) {
            // Usa l'istanza del servizio per calcolare il prezzo della risorsa
            $resourcePrice = $priceService->calculateResourcePrice($resourceType, $square);

            if ($eventType == 'crisi') {
                $resourcePrice += ($resourcePrice * ($percentageChange / 100)); // Aumenta il prezzo
            } elseif ($eventType == 'abbondanza') {
                $resourcePrice -= ($resourcePrice * ($percentageChange / 100)); // Riduce il prezzo
            }

            // Log degli eventi di risorse
            EventLog::create([
                'event_type' => 'resource_event',
                'details' => json_encode([
                    'resource_type' => $resourceType,
                    'event' => $eventType,
                    'price_change' => $percentageChange,
                ]),
                'event_context' => json_encode(['map_square_id' => $square->id]),
                'created_at' => now(),
            ]);
        }
    }


    public function investInRealEstate(Character $character, $amount)
    {
        if ($character->cash < $amount) {
            return "{$character->username} non ha abbastanza denaro per investire in immobili.";
        }

        // Riduce il denaro del personaggio
        $character->cash -= $amount;
        $character->save();

        // Simula il tasso di rendimento (es. 5% annuo) e la durata (es. 12 mesi, corrispondenti a 288 giorni)
        $returnRate = rand(3, 8); // Percentuale tra il 3% e l'8%
        $duration = 432; // Durata in giorni, equivalente a 18 mesi

        // Crea l'investimento
        Investment::create([
            'character_id' => $character->id,
            'amount' => $amount,
            'types' => 'immobiliare',
            'current_value' => $amount,
            'return_rate' => $returnRate,
            'duration' => $duration,  // Durata espressa in giorni del tuo sistema
            'status' => 'active',
            'stipulated_at' => now(),
        ]);

        EventLog::logInvestmentTransaction($character, 'immobiliare', $amount, $returnRate);

        return "{$character->username} ha investito {$amount} AA in immobili con un rendimento annuo del {$returnRate}%.";
    }

    public function simulateInvestmentReturns()
    {
        $investments = Investment::where('status', 'active')->get();

        foreach ($investments as $investment) {
            // Calcola il nuovo valore basato sul tasso di rendimento e la durata rimanente
            $newValue = $investment->current_value + ($investment->current_value * ($investment->return_rate / 100));

            // Aggiorna il valore dell'investimento e riduce la durata
            $investment->current_value = $newValue;
            $investment->duration -= 24; // Adattato: riduzione di 24 giorni per rappresentare 1 mese del tuo sistema

            // Se la durata è finita, completa l'investimento
            if ($investment->duration <= 0) {
                $investment->status = 'completed';
                $investment->completed_at = now();
            }

            $investment->save();

            // Log del rendimento ricevuto
            EventLog::logInvestmentReturn($investment->character, $investment);
        }
    }

    public function dailyResourceConsumption(Character $character)
    {
        // Definisci quali risorse vengono consumate giornalmente
        $resourcesToConsume = [
            'cibo' => 5, // Consumo giornaliero di cibo
            'acqua' => 3, // Consumo giornaliero di acqua
            'carburante' => 1 // Consumo giornaliero di carburante
        ];

        // Itera su ogni risorsa da consumare
        foreach ($resourcesToConsume as $resourceType => $dailyAmount) {
            $resource = Resource::where('character_id', $character->id)
                ->where('type', $resourceType)
                ->first();

            // Se la risorsa esiste e c'è abbastanza quantità
            if ($resource && $resource->amount >= $dailyAmount) {
                $resource->amount -= $dailyAmount;
                $resource->save();

                // Log del consumo delle risorse
                EventLog::create([
                    'character_id' => $character->id,
                    'event_type' => 'resource_consumed',
                    'details' => json_encode([
                        'resource_type' => $resourceType,
                        'quantity' => $dailyAmount,
                        'remaining' => $resource->amount
                    ]),
                    'event_context' => json_encode(['city_state' => 'daily_consumption']),
                    'created_at' => now(),
                ]);
            } else {
                // Log di una possibile crisi di risorse (non abbastanza risorse)
                EventLog::create([
                    'character_id' => $character->id,
                    'event_type' => 'resource_shortage',
                    'details' => json_encode([
                        'resource_type' => $resourceType,
                        'required' => $dailyAmount,
                        'available' => $resource ? $resource->amount : 0
                    ]),
                    'event_context' => json_encode(['city_state' => 'resource_crisis']),
                    'created_at' => now(),
                ]);
            }
        }
    }


    protected function getResourceAvailability(MapSquare $square, $resourceType)
    {
        // Simula la disponibilità in base al numero di negozi o risorse disponibili nel rione
        $commercialCount = $square->current_buildings_of_type('commercial');

        // Più negozi ci sono, maggiore è la disponibilità, influenzando il prezzo
        return $commercialCount * 2;  // Ad esempio, ogni negozio aumenta la disponibilità
    }
}










// public function calculatePropertyPrice(MapSquare $square)
// {
//     $basePrice = 100000;  // Prezzo di base per le proprietà immobiliari
//     $multiplier = $square->socio_economic_status / 100;

//     // Calcola il prezzo in base allo stato socio-economico del quartiere
//     return $basePrice * $multiplier;
// }

// public function handleCharacterTransaction(Character $character, $amount)
// {
//     // Verifica se il personaggio ha abbastanza denaro
//     if ($character->cash < $amount) {
//         return "Transazione fallita: non ci sono fondi sufficienti.";
//     }

//     // Esegui la transazione
//     $character->cash -= $amount;
//     $character->save();

//     return "Il personaggio {$character->username} ha effettuato una transazione di {$amount} AA.";
// }

// public function investInBusiness(Character $character, $investmentAmount)
// {
//     // Simula un ritorno dell'investimento
//     $return = rand(-$investmentAmount, $investmentAmount * 2);

//     $character->cash += $return;
//     $character->save();

//     return "Il personaggio {$character->username} ha investito {$investmentAmount} AA e ha ricevuto un ritorno di {$return} AA.";
// }

attributes:
