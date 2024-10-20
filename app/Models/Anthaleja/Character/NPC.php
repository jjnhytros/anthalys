<?php

namespace App\Models\Anthaleja\Character;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\MegaWareHouse\Warehouse;

class NPC extends Model
{
    public $table = 'warehouse_npc';
    protected $fillable = [
        'name',
        'role',
        'status',
        'warehouse_id',
        'skill_level'  // Nuovo campo per monitorare il livello di abilità
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function assignTask($task, $priority = 1)
    {
        $this->current_task = $task;
        $this->task_priority = $priority;
        $this->save();
    }


    public function receiveNotification($message)
    {
        // Logica per gestire la ricezione di notifiche
        echo "{$this->name} received notification: {$message}";
    }

    public function interactWithPlayer($playerAction)
    {
        switch ($playerAction) {
            case 'ask_for_help':
                $this->updateReputation($this, 'completed_mission'); // Esempio di aggiornamento della reputazione
                return "{$this->name} is willing to help you.";
            case 'offer_job':
                $this->updateReputation($this, 'completed_mission');
                return "{$this->name} is interested in the job offer.";
            default:
                return "{$this->name} has nothing to say.";
        }
    }

    public function train()
    {
        // Incrementa il livello di abilità
        $this->skill_level += 1;
        $this->save();

        echo "{$this->name} has completed training and improved their skills.";
    }
}
