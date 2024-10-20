<?php

namespace App\Models\Anthaleja\Bank;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Definisce i campi che possono essere riempiti tramite mass assignment
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'amount',
        'master_type',
        'type',
        'status',
        'commission_amount',
        'description',
        'notification_sent'
    ];

    // Stati possibili per la transazione
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_APPROVED = 'approved';
    const STATUS_COMPLETED = 'completed';

    /**
     * Relazione tra la transazione e il mittente.
     * Una transazione appartiene a un personaggio che agisce come mittente.
     */
    public function sender()
    {
        // Definisce la relazione many-to-one tra la transazione e il character mittente
        return $this->belongsTo(Character::class, 'sender_id');
    }

    /**
     * Relazione tra la transazione e il destinatario.
     * Una transazione appartiene a un personaggio che agisce come destinatario.
     */
    public function recipient()
    {
        // Definisce la relazione many-to-one tra la transazione e il character destinatario
        return $this->belongsTo(Character::class, 'recipient_id');
    }
    public function commission()
    {
        return $this->hasOne(Commission::class);
    }
    public function confirm()
    {
        $this->status = 'confirmed';
        $this->save();
    }
    public function approve()
    {
        $this->status = 'approved';
        $this->save();
    }
    public function calculateCommission()
    {
        if ($this->amount >= 1000) {
            // Calcolo delle commissioni in base agli importi
            $commissionRate = $this->getCommissionRate($this->amount);
            $this->commission_amount = $this->amount * $commissionRate;
            $this->save();

            // Creare la commissione
            $this->createCommission();
        }
    }
    private function getCommissionRate($amount)
    {
        if ($amount > 1000000) {
            return 0.008;
        } elseif ($amount >= 100000) {
            return 0.004;
        } elseif ($amount >= 10000) {
            return 0.002;
        } elseif ($amount >= 1000) {
            return 0.001;
        }
        return 0;
    }
    public function createCommission()
    {
        $governmentShare = $this->commission_amount * 0.24;  // 24% al governo (Character 2)
        $bankShare = $this->commission_amount * 0.76;  // 76% alla banca (Character 4)

        $this->commission()->create([
            'transaction_id' => $this->id,
            'government_share' => $governmentShare,
            'bank_share' => $bankShare,
        ]);
    }
}
