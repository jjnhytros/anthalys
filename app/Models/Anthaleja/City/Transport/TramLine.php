<?php

namespace App\Models\Anthaleja\City\Transport;

use Illuminate\Database\Eloquent\Model;

class TramLine extends Model
{
    protected $fillable = ['line_name', 'stops'];

    protected $casts = [
        'stops' => 'array',  // Cast per l'attributo fermate come array
    ];
}
