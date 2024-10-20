<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetRoomMessage extends Model
{
    public $table = "sonet_room_messages";

    public function room()
    {
        return $this->belongsTo(SonetRoom::class);
    }

    public function sender()
    {
        return $this->belongsTo(Character::class, 'sender_id');
    }
}
