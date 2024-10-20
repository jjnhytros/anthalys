<?php

namespace App\Models\Anthaleja;

use Illuminate\Database\Eloquent\Model;

class RelationshipName extends Model
{
    public $table = 'relationship_names';
    protected $fillable = [
        'name',
        'required_existing',
        'override',
        'description',
    ];
}
