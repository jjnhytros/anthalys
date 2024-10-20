<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetRoom extends Model
{
    public $table = "sonet_rooms";

    public function members()
    {
        return $this->hasMany(SonetRoomMember::class);
    }

    public function creator()
    {
        return $this->belongsTo(Character::class, 'created_by');
    }

    public function messages()
    {
        return $this->hasMany(SonetRoomMessage::class);
    }

    public function isAdmin($characterId)
    {
        return $this->members()->where('character_id', $characterId)->where('role', 'admin')->exists();
    }

    public function isModerator($characterId)
    {
        return $this->members()->where('character_id', $characterId)->whereIn('role', ['admin', 'moderator'])->exists();
    }
}
