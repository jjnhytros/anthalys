<?php

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class Mission extends Model
{
    protected $fillable = [
        'character_id',
        'title',
        'description',
        'status',
        'assigned_at',
        'completed_at'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
