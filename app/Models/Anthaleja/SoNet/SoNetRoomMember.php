<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetRoomMember extends Model
{
    public $table = "sonet_room_members";
    protected $fillable = ['room_id', 'character_id', 'role'];

    public function room()
    {
        return $this->belongsTo(SonetRoom::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
