<?php

namespace App\Models\Anthaleja\Bank;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = ['transaction_id', 'government_share', 'bank_share'];

    // Relazione con il modello Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relazione con il character per il governo e la banca
    public function government()
    {
        return $this->belongsTo(Character::class, 'government_id'); // Character 2
    }

    public function bank()
    {
        return $this->belongsTo(Character::class, 'bank_id'); // Character 4
    }
}
