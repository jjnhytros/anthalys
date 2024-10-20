<?php

namespace App\Models\Anthaleja\Character;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\SoNet\SoNetLuminum;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\SoNet\SoNetPortfolio;

class Profile extends Model
{
    protected $fillable = [
        'character_id',
        'bio',
        'profile_picture',
        'link',
        'skills',
        'verified',
        'privacy',
        'preferences',
    ];

    // Cast per il campo skills in formato array
    protected $casts = [
        'skills' => 'array', // Gestisce il campo JSON delle abilità
        'preferences' => 'array', // Gestisce il campo JSON delle preferenze
        'verified' => 'boolean',
        'privacy' => 'string',
    ];

    // Relazione con il personaggio (Character)
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    // Relazione con i portfolio
    public function portfolios()
    {
        return $this->hasMany(SoNetPortfolio::class);
    }

    public function lumina()
    {
        return $this->morphMany(SoNetLuminum::class, 'luminable');
    }


    // Esempio di metodo per aggiornare il profilo (può essere implementato)
    public function updateProfile(array $data)
    {
        try {
            $this->update($data);
        } catch (\Exception $e) {
            // Notifica di errore durante l'aggiornamento
            dd('Error updating profile: ' . $e->getMessage());
        }
    }
}
