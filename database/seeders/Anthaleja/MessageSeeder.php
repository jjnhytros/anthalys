<?php

namespace Database\Seeders\Anthaleja;

use Illuminate\Database\Seeder;
use App\Models\Anthaleja\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crea una notifica di esempio
        Message::create([
            'sender_id' => 2,
            'recipient_id' => 3,
            'subject' => 'Investimento Completato',
            'message' => 'Il tuo investimento è stato completato con successo.',
            'is_notification' => true,
            'status' => 'unread',
        ]);
    }
}
