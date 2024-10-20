<?php

namespace App\Models\Anthaleja\Games;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'suit',    // hearts, diamonds, clubs, spades, moons, stars
        'color',   // red, black, yellow
        'value',   // 2-12, H, J, W, K, T, A
        'is_joker' // boolean to identify if it's a joker
    ];

    // Function to get the corresponding icon for the suit
    public function getIcon()
    {
        switch ($this->suit) {
            case 'hearts':
                return 'suit-heart-fill';
            case 'diamonds':
                return 'suit-diamond-fill';
            case 'clubs':
                return 'suit-club-fill';
            case 'spades':
                return 'suit-spade-fill';
            case 'moons':
                return 'moon-fill';
            case 'stars':
                return 'circle-fill';
            default:
                return 'question-circle'; // default icon
        }
    }
}
