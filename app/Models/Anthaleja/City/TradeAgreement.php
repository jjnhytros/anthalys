<?php

namespace App\Models\Anthaleja\City;

use Illuminate\Database\Eloquent\Model;

class TradeAgreement extends Model
{

    protected $fillable = [
        'from_square_id',
        'to_square_id',
        'resource_type',
        'quantity',
        'duration',
        'status'
    ];

    // Relazioni con i MapSquare
    public function fromSquare()
    {
        return $this->belongsTo(MapSquare::class, 'from_square_id');
    }

    public function toSquare()
    {
        return $this->belongsTo(MapSquare::class, 'to_square_id');
    }
}
