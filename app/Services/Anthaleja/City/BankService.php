<?php

namespace App\Services\Anthaleja\City;

use App\Models\Anthaleja\Character\Character;

class BankService
{
    public function deposit(Character $character, $amount)
    {
        if ($character->cash >= $amount) {
            $character->cash -= $amount;
            $character->bank += $amount;
            $character->save();

            return "Deposito completato: {$amount} AA.";
        } else {
            return "Fondi insufficienti per depositare.";
        }
    }

    public function withdraw(Character $character, $amount)
    {
        if ($character->bank >= $amount) {
            $character->bank -= $amount;
            $character->cash += $amount;
            $character->save();

            return "Prelievo completato: {$amount} AA.";
        } else {
            return "Fondi insufficienti in banca.";
        }
    }

    public function requestLoan(Character $character, $amount)
    {
        // Aggiungi logica per calcolare un prestito (esempio: limite massimo o interessi)
        if ($amount <= 5000) {  // Limite di prestito di 5000 AA
            $character->loan_amount += $amount;
            $character->cash += $amount;
            $character->save();

            return "Prestito approvato: {$amount} AA. Ricorda di ripagarlo!";
        } else {
            return "Il prestito richiesto è troppo alto.";
        }
    }
}
