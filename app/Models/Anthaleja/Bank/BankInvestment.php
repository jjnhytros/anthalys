<?php

namespace App\Models\Anthaleja\Bank;

use App\Models\Anthaleja\Message;
use Illuminate\Support\Facades\DB;
use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;
use App\Services\ATHDateTime\ATHDateTime;

class BankInvestment extends Model
{
    protected $table = 'investments'; // Tabella associata agli investimenti

    protected $fillable = [
        'character_id', // ID del personaggio che effettua l'investimento
        'amount', // Importo investito
        'types', // Tipologia dell'investimento
        'return_rate', // Tasso di ritorno
        'duration', // Durata dell'investimento
        'status', // Stato dell'investimento (es. completato)
        'completed_at', // Data di completamento dell'investimento
    ];

    /**
     * Relazione con il personaggio che ha fatto l'investimento.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Calcola il ritorno sull'investimento in base al tipo di rischio.
     * @param mixed $someArgument Argomento opzionale, può essere utilizzato per estensioni future.
     */
    public function calculateReturn($someArgument = null)
    {
        try {
            // Determina il tasso di ritorno in base al tipo di investimento
            switch ($this->type) {
                case 'low_risk':
                    $this->return_rate = config('ath.bank.investment.low_risk_return_rate');
                    break;

                case 'medium_risk':
                    $this->return_rate = config('ath.bank.investment.medium_risk_return_rate');
                    break;

                case 'high_risk':
                    $this->return_rate = $this->determineHighRiskReturn();
                    break;
            }

            // Aggiunge il ritorno all'importo investito
            $this->amount += $this->amount * $this->return_rate;
            $this->save();
        } catch (\Exception $e) {
            dd('Errore nel calcolo del ritorno: ' . $e->getMessage());
        }
    }

    /**
     * Calcola il ritorno per ogni tipo di investimento, se ci sono più tipi.
     */
    public function calculateReturnForTypes()
    {
        try {
            $types = explode(',', $this->types); // Gestisce i tipi multipli di investimento
            foreach ($types as $type) {
                $this->calculateReturn($type); // Calcola il ritorno per ciascun tipo
            }
        } catch (\Exception $e) {
            dd('Errore nel calcolo del ritorno per i tipi: ' . $e->getMessage());
        }
    }

    /**
     * Determina il ritorno per gli investimenti ad alto rischio, applicando vari fattori dinamici.
     */
    public function determineHighRiskReturn()
    {
        try {
            $min = config('ath.bank.investment.high_risk_min_return_rate') * 100;
            $max = config('ath.bank.investment.high_risk_max_return_rate') * 100;
            $currentSeason = (new ATHDateTime())->getCurrentSeason();
            $currentMonth = (new ATHDateTime())->getCurrentMonthName(); // Ottieni il mese corrente

            // Fattori dinamici che influenzano il tasso di ritorno
            $marketMultiplier = $this->getDynamicMarketMultiplier($currentMonth, $currentSeason);
            $inflationRate = $this->getDynamicInflationRate($currentMonth, $currentSeason);
            $sectorMultiplier = $this->getDynamicSectorMultiplier('tech', $currentSeason);
            $geoMultiplier = $this->getDynamicGeoMultiplier($currentMonth, $currentSeason);
            $envMultiplier = $this->getDynamicEnvironmentalMultiplier($currentMonth, $currentSeason);
            $crisisMultiplier = $this->getDynamicCrisisMultiplier($currentMonth, $currentSeason);
            $seasonMultiplier = $this->getDynamicSeasonMultiplier($currentSeason);
            $successChance = $this->getDynamicSuccessChance($currentMonth, $currentSeason);

            // Determina il tasso di ritorno basato sulla probabilità di successo
            if ($successChance < 70) {
                $returnRate = mt_rand($min, $max) / 100;
            } else {
                $returnRate = mt_rand($min, 0) / 100;
            }

            // Applica i fattori dinamici al tasso di ritorno
            $returnRate = ($returnRate * $marketMultiplier * $sectorMultiplier * $geoMultiplier * $envMultiplier * $seasonMultiplier * $crisisMultiplier) - $inflationRate;

            // Applica l'Indice di Sviluppo (IDS)
            $idsValue = Ids::latest()->first()->value;
            $returnRate *= $idsValue;

            return $returnRate;
        } catch (\Exception $e) {
            dd('Errore nel calcolo del ritorno ad alto rischio: ' . $e->getMessage());
            return 0; // In caso di errore, restituisce 0
        }
    }

    /**
     * Gestisce il completamento dell'investimento, trasferisce i fondi al personaggio e invia le notifiche.
     */
    public function processInvestmentCompletion()
    {
        DB::beginTransaction();
        try {
            // Calcola l'importo finale da accreditare
            $finalAmount = $this->amount * (1 + $this->return_rate);
            $this->character->bank += $finalAmount;
            $this->character->save();

            // Aggiorna lo stato dell'investimento
            $this->status = 'completed';
            $this->completed_at = now();
            $this->save();
            DB::commit();

            // Invia un messaggio di successo al personaggio
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->character_id,
                'subject' => 'Investment Completed',
                'message' => "Your investment of type {$this->type} has been successfully completed.",
                'is_notification' => true,
                'status' => 'unread',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Invia un messaggio di fallimento al personaggio
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->character_id,
                'subject' => 'Investment Failed',
                'message' => "Unfortunately, your investment of type {$this->type} has failed.",
                'is_notification' => true,
                'status' => 'unread',
            ]);
        }
    }

    /**
     * Moltiplicatore dinamico del mercato in base alla stagione.
     */
    protected function getDynamicMarketMultiplier($currentMonth, $currentSeason)
    {
        $marketState = mt_rand(0, 100);

        if ($currentSeason === 'Winter') {
            // Il mercato è generalmente più ribassista in inverno
            return $marketState < 50 ? 0.8 : 1.1;
        } elseif ($currentSeason === 'Summer') {
            // Il mercato tende ad essere più rialzista in estate
            return $marketState > 50 ? 1.3 : 0.9;
        } else {
            return $marketState < 30 ? 0.9 : 1.2;
        }
    }

    /**
     * Tasso di inflazione dinamico in base alla stagione.
     */
    protected function getDynamicInflationRate($currentMonth, $currentSeason)
    {
        if ($currentSeason === 'Spring') {
            // Inflazione bassa in primavera
            return mt_rand(0, 5) / 100;
        } elseif ($currentSeason === 'Autumn') {
            // Inflazione più alta in autunno
            return mt_rand(5, 10) / 100;
        } else {
            return mt_rand(0, 10) / 100;
        }
    }

    /**
     * Moltiplicatore geopolitico dinamico in base alla stagione.
     */
    protected function getDynamicGeoMultiplier($currentMonth, $currentSeason)
    {
        $geoEvents = mt_rand(0, 100);

        if ($currentSeason === 'Summer') {
            // Maggiore probabilità di eventi geopolitici positivi in estate
            return $geoEvents < 30 ? 1.3 : 1;
        } elseif ($currentSeason === 'Winter') {
            // Maggiore probabilità di eventi geopolitici negativi in inverno
            return $geoEvents > 70 ? 0.7 : 1;
        } else {
            return $geoEvents < 15 ? 1.3 : ($geoEvents > 85 ? 0.7 : 1);
        }
    }

    /**
     * Moltiplicatore dinamico per gli eventi ambientali.
     */
    protected function getDynamicEnvironmentalMultiplier($currentMonth, $currentSeason)
    {
        $environmentalEvents = mt_rand(0, 100);

        if ($currentMonth === '7' || $currentMonth === '8') {
            // Alta probabilità di eventi ambientali positivi nei mesi 7 e 8
            return $environmentalEvents < 25 ? 1.5 : 1;
        } elseif ($currentSeason === 'Winter') {
            // Maggiore rischio di disastri ambientali in inverno
            return $environmentalEvents > 70 ? 0.6 : 1;
        } else {
            return $environmentalEvents < 20 ? 1.4 : ($environmentalEvents > 80 ? 0.6 : 1);
        }
    }

    /**
     * Moltiplicatore dinamico per crisi globali.
     */
    protected function getDynamicCrisisMultiplier($currentMonth, $currentSeason)
    {
        $globalCrisis = mt_rand(0, 100);

        if ($currentSeason === 'Winter') {
            return $globalCrisis > 80 ? 0.5 : 1;
        } elseif ($currentSeason === 'Spring') {
            return $globalCrisis < 20 ? 1.5 : 1;
        } else {
            return $globalCrisis > 90 ? 0.5 : 1;
        }
    }

    /**
     * Calcola la probabilità di successo dinamica in base alla stagione.
     */
    protected function getDynamicSuccessChance($currentMonth, $currentSeason)
    {
        if ($currentSeason === 'Summer') {
            return mt_rand(0, 100) + 10;
        } elseif ($currentSeason === 'Winter') {
            return mt_rand(0, 100) - 10;
        } else {
            return mt_rand(0, 100);
        }
    }

    /**
     * Calcola il moltiplicatore stagionale dinamico.
     */
    protected function getDynamicSeasonMultiplier($currentSeason)
    {
        switch ($currentSeason) {
            case 'Spring':
                return 1 + (rand(5, 10) / 100);
            case 'Summer':
                return 1 + (rand(10, 15) / 100);
            case 'Autumn':
                return rand(95, 98) / 100;
            case 'Winter':
                return rand(90, 95) / 100;
            default:
                return 1;
        }
    }

    /**
     * Moltiplicatore di settore dinamico in base al settore e alla stagione.
     */
    protected function getDynamicSectorMultiplier($selectedSector, $currentSeason)
    {
        switch ($selectedSector) {
            case 'tech':
                return ($currentSeason === 'Spring' || $currentSeason === 'Summer') ? 1.375 : 1.175;
            case 'real_estate':
                return ($currentSeason === 'Autumn' || $currentSeason === 'Winter') ? 0.875 : 1.1;
            case 'energy':
                return ($currentSeason === 'Winter') ? 1.325 : 1.05;
            case 'finance':
                return ($currentSeason === 'Spring' || $currentSeason === 'Autumn') ? 1.05 : 0.925;
            case 'agriculture':
                return ($currentSeason === 'Spring') ? 1.5 : 0.9;
            case 'tourism':
                return ($currentSeason === 'Summer') ? 1.6 : 0.875;
            default:
                return 1;
        }
    }
}
