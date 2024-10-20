<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Bank\Transaction;

class SoNetDonation extends Model
{
    protected $fillable = ['transaction_id', 'message', 'incentive_id'];

    // Relazione con la transazione
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relazione con un eventuale incentivo
    // public function incentive()
    // {
    //     return $this->belongsTo(Incentive::class);
    // }
}
