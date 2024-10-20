<?php

namespace App\Models\Anthaleja\Phone;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    // Definisce la tabella associata al modello
    protected $table = 'applications';

    // Campi assegnabili in massa
    protected $fillable = ['icon', 'name', 'link', 'status'];
}
