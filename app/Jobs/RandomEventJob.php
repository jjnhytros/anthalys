<?php

namespace App\Jobs;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RandomEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $character;

    public function __construct(Character $character)
    {
        $this->character = $character;
    }

    public function handle()
    {
        // Genera un evento casuale
        $eventType = $this->generateRandomEvent();

        switch ($eventType) {
            case 'win_lottery':
                $this->handleLotteryWin();
                break;
            case 'health_issue':
                $this->handleHealthIssue();
                break;
            case 'promotion':
                $this->handlePromotion();
                break;
            case 'economic_crisis':
                $this->handleEconomicCrisis();
                break;
        }

        $this->character->save();
    }

    protected function generateRandomEvent()
    {
        // Probabilità personalizzate in base agli attributi del personaggio
        if ($this->character->work_level > 3) {
            return 'promotion';  // Se il livello di lavoro è alto, più probabile una promozione
        } elseif ($this->character->cash < 1000) {
            return 'health_issue';  // Se ha pochi soldi, potrebbe avere problemi di salute
        } else {
            $events = ['win_lottery', 'economic_crisis'];  // Altri eventi casuali
            return $events[array_rand($events)];
        }
    }

    protected function handleLotteryWin()
    {
        // Il personaggio vince alla lotteria
        $this->character->cash += 5000;
        echo "Il personaggio ha vinto la lotteria! +5000 cash\n";
    }

    protected function handleHealthIssue()
    {
        // Il personaggio ha problemi di salute (es. riduzione del cash per spese mediche)
        if ($this->character->cash > 2000) {
            $this->character->cash -= 2000;
            echo "Il personaggio ha avuto problemi di salute. -2000 cash\n";
        } else {
            echo "Il personaggio non ha abbastanza fondi per pagare le cure.\n";
        }
    }

    protected function handlePromotion()
    {
        // Il personaggio viene promosso sul lavoro (incrementa il livello di lavoro)
        $this->character->work_level += 1;
        $this->character->cash += 1000;  // Aumento di stipendio con la promozione
        echo "Il personaggio è stato promosso! Livello di lavoro aumentato e +1000 cash\n";
    }

    protected function handleEconomicCrisis()
    {
        // Crisi economica: riduzione del denaro in banca
        if ($this->character->bank > 3000) {
            $this->character->bank -= 3000;
            echo "Crisi economica! -3000 dal conto in banca\n";
        } else {
            echo "Il personaggio ha evitato la crisi perché ha pochi fondi in banca.\n";
        }
    }
}
