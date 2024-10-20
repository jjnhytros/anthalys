<?php

namespace App\Services;

class NotificationService
{
    /**
     * Invia una notifica al personaggio sugli effetti del meteo.
     *
     * @param Character $character Il personaggio che riceve la notifica.
     * @param string $weatherCondition La condizione meteorologica.
     */
    public function sendWeatherEffectNotification($character, $weatherCondition)
    {
        // Titolo della notifica
        $title = __('messages.weather_update', ['weather' => $weatherCondition]);

        // Messaggio della notifica con i cambiamenti nelle statistiche
        $message = __('messages.weather_stats_change', [
            'weather' => $weatherCondition,
            'energy' => $character->energy,
            'happiness' => $character->happiness,
            'hydration' => $character->hydration
        ]);

        // Creazione della notifica
        Notification::create([
            'character_id' => $character->id,
            'title' => $title,
            'message' => $message,
            'read' => false,
        ]);
    }

    /**
     * Invia una notifica per un'attività completata o fallita.
     *
     * @param Character $character Il personaggio che riceve la notifica.
     * @param object $activity L'attività associata.
     * @param string $result Il risultato dell'attività ('completed', 'failed').
     */
    public function sendActivityNotification($character, $activity, $result)
    {
        // Titolo della notifica
        $title = __('messages.activity_notification', ['activity' => $activity->name, 'result' => $result]);

        // Messaggio della notifica
        $message = __('messages.activity_marked', ['activity' => $activity->name, 'result' => $result]);

        // Aggiungi un messaggio aggiuntivo in base al risultato
        if ($result === 'completed') {
            $message .= __('messages.activity_stats_improved');
        } else {
            $message .= __('messages.activity_failed_2');
        }

        // Creazione della notifica
        Notification::create([
            'character_id' => $character->id,
            'title' => $title,
            'message' => $message,
            'read' => false,
        ]);
    }

    /**
     * Invia una notifica generica al personaggio.
     *
     * @param Character $character Il personaggio che riceve la notifica.
     * @param string $message Il messaggio della notifica.
     */
    public function sendNotification(Character $character, $message)
    {
        // Creazione della notifica
        Notification::create([
            'character_id' => $character->id,
            'message' => $message,
            'read' => false,
        ]);
    }

    /**
     * Segna una notifica come letta.
     *
     * @param Notification $notification La notifica da aggiornare.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->read = true;
        $notification->save();
    }

    /**
     * Ottiene tutte le notifiche non lette per un personaggio.
     *
     * @param Character $character Il personaggio di cui ottenere le notifiche.
     * @return \Illuminate\Database\Eloquent\Collection Le notifiche non lette.
     */
    public function getUnreadNotifications(Character $character)
    {
        return Notification::where('character_id', $character->id)
            ->where('read', false)
            ->get();
    }

    /**
     * Notifica al personaggio un aumento di reputazione.
     *
     * @param Character $character Il personaggio che riceve la notifica.
     */
    public function notifyReputationIncrease(Character $character)
    {
        $this->sendNotification($character, __('messages.reputation_increased'));
    }

    /**
     * Notifica al personaggio il progresso in una specializzazione.
     *
     * @param Character $character Il personaggio che riceve la notifica.
     * @param Specialization $specialization La specializzazione su cui inviare l'aggiornamento.
     */
    public function notifySpecializationProgress(Character $character, Specialization $specialization)
    {
        $this->sendNotification($character, __('messages.specialization_progress', [
            'level' => $specialization->level,
            'specialization' => $specialization->name
        ]));
    }
}
