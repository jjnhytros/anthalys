<?php

namespace App\Models\Anthaleja\Character;

use App\Models\User;
use App\Models\Anthaleja\Relationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\SoNet\SoNetPost;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Models\Anthaleja\SoNet\SoNetReview;
use App\Models\Anthaleja\SoNet\SoNetMessage;
use App\Models\Anthaleja\SoNet\SoNetChatRoom;
use App\Models\Anthaleja\SoNet\SoNetJobOffer;
use App\Models\Anthaleja\SoNet\SoNetConnection;

class Character extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'api_token',
        'first_name',
        'last_name',
        'username',
        'email',
        'resources',
        'attributes',
        'cash',
        'bank',
        'bank_account',
        'have_phone',
        'phone_number',
        'crafting_skill',
        'trading_skill',
        'work_level',
        'loyalty',
        'reputation',
        'emergency_balance',
        'loan_amount',
        'loan_interest',
        'loan_due_date',
        'status',
        'is_online',
        'is_npc',
    ];

    protected $casts = [
        'resources' => 'array', // Gestisce il campo JSON delle risorse
        'attributes' => 'array', // Gestisce il campo JSON degli attributi
        'status' => 'boolean',
        'is_online' => 'boolean',
        'cash' => 'decimal:2',
        'bank' => 'decimal:2',
        'emergency_balance' => 'decimal:2',
        'loan_amount' => 'decimal:2',
        'loan_interest' => 'decimal:2',
        'loan_due_date' => 'date',
    ];

    // Relationships
    public function articles()
    {
        return $this->hasMany(WikiArticle::class);
    }
    public function chatRooms()
    {
        return $this->hasMany(SoNetChatRoom::class, 'created_by');
    }
    public function children()
    {
        return $this->relatedCharacters()->wherePivot('relationship_type', 'child');
    }
    public function connections()
    {
        return $this->belongsToMany(Character::class, 'sonet_connections', 'sender_id', 'recipient_id')
            ->wherePivot(
                'status',
                'accepted'
            );
    }
    public function givenReviews()
    {
        return $this->hasMany(SoNetReview::class, 'reviewer_id');
    }
    public function mapSquare()
    {
        return $this->belongsTo(MapSquare::class);
    }
    public function parents()
    {
        return $this->relatedCharacters()->wherePivot('relationship_type', 'parent');
    }
    public function partners()
    {
        return $this->relatedCharacters()->wherePivot('relationship_type', 'partner');
    }
    public function pendingConnections()
    {
        return $this->hasMany(SoNetConnection::class, 'sender_id')->where('status', 'pending');
    }
    public function posts()
    {
        return $this->hasMany(SoNetPost::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function receivedConnectionRequests()
    {
        return $this->hasMany(SonetConnection::class, 'recipient_id')->where('status', 'pending');
    }
    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }
    public function relatedCharacters()
    {
        return $this->belongsToMany(Character::class, 'character_relationships', 'character_id', 'related_character_id')
            ->withPivot('relationship_type')
            ->withTimestamps();
    }
    public function reviews()
    {
        return $this->hasMany(SoNetReview::class);
    }
    public function siblings()
    {
        return $this->relatedCharacters()->wherePivot('relationship_type', 'sibling');
    }
    public function sonetMessages()
    {
        return $this->hasMany(SoNetMessage::class, 'sender_id');
    }
    public function spouses()
    {
        return $this->relatedCharacters()->wherePivot('relationship_type', 'spouse');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function interactWith(Character $otherCharacter)
    {
        $interactionService = new \App\Services\InteractionService();
        $interactionService->createInteraction($this, $otherCharacter);
    }


    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }


    // Booleans
    public function isAdminInRoom($roomId)
    {
        $member = $this->roomMemberships()->where('room_id', $roomId)->first();
        return $member && $member->role === 'admin';
    }
    public function isModeratorInRoom($roomId)
    {
        $member = $this->roomMemberships()->where('room_id', $roomId)->first();
        return $member && in_array($member->role, ['admin', 'moderator']);
    }
    public function isNPC()
    {
        return $this->is_npc; // True se il personaggio è un NPC
    }

    // Referrals
    public function jobRecommendations()
    {
        $skills = $this->profile->skills ?? [];
        return SoNetJobOffer::where(function ($query) use ($skills) {
            foreach ($skills as $skill) {
                $query->orWhereJsonContains('required_skills', $skill);
            }
        })->get();
    }

    // Ads preference
    public function prefersAds()
    {
        $preferences = json_decode($this->profile->preferences, true);
        return isset($preferences['show_ads']) ? $preferences['show_ads'] : false;
    }







    // Getters & Setters
    public function getAttributesField($key)
    {
        return $this->attributes['attributes'][$key] ?? null;
    }


    public function setAttributesField($key, $value)
    {
        $attributes = $this->attributes['attributes'] ?? [];
        $attributes[$key] = $value;
        $this->attributes['attributes'] = $attributes;
    }


    // Commented out methods (potentially useful in the future)
    /*
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function objekts()
    {
        return $this->hasMany(Objekt::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function dailyActions()
    {
        return $this->hasMany(DailyAction::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    */

    // Skill management methods
    /*
    public function increaseCraftingSkill($points = 1)
    {
        $this->crafting_skill += $points;
        $this->save();
    }

    public function increaseTradingSkill($points = 1)
    {
        $this->trading_skill += $points;
        $this->save();
    }

    public function increaseWorkExperience($points = 1)
    {
        $this->work_experience += $points;
        $this->save();
    }
    */

    // Example methods for leveling or increasing experience points
    /*
    public function increaseWorkLevel()
    {
        if ($this->work_experience >= 100 * $this->work_level) {
            $this->work_level += 1;
            $this->save();
        }
    }
    */
}
