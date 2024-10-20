<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\Anthaleja\Character\Character;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles; // Abilita l'uso di Notifiable e HasRoles

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = [
        'username',              // Nome dell'utente
        'email',             // Email dell'utente
        'password',          // Password dell'utente
        'roles',             // Ruoli dell'utente
    ];

    // I campi da nascondere quando l'istanza viene convertita in array o JSON
    protected $hidden = [
        'password',          // Nascondi la password
        'remember_token',    // Nascondi il token di "ricordami"
    ];

    // Casta i campi in tipi specifici
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Cast della data di verifica email
            'password' => 'hashed',            // Cast della password
            'roles' => 'array',                // Cast dei ruoli come array
        ];
    }

    // Relazione con il modello Character
    public function character()
    {
        return $this->hasOne(Character::class);
    }

    // Verifica se l'utente ha un determinato ruolo
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    // Assegna un ruolo governativo all'utente
    public static function assignGovernmentRole($userId)
    {
        // Controlla se il ruolo 2 è già assegnato a un altro utente
        if (User::whereJsonContains('roles', 2)->exists()) {
            throw new \Exception('Role 2 is already assigned to another user.'); // Errore se il ruolo è già assegnato
        }

        $user = User::find($userId); // Trova l'utente per ID
        if ($user) {
            // Aggiungi il ruolo 2 ai ruoli esistenti, mantenendo solo valori unici
            $user->roles = array_unique(array_merge($user->roles, [2]));
            $user->save(); // Salva le modifiche
        }
    }
}
