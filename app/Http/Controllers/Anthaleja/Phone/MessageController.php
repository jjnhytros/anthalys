<?php

namespace App\Http\Controllers\Anthaleja;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function inbox(Request $request)
    {
        $characterId = Auth::user()->character->id;
        $type = $request->routeIs('messages.inbox') ? 'messages' : ($request->routeIs('emails.inbox') ? 'emails' : 'notifications');

        $query = Message::where('recipient_id', $characterId);

        switch ($type) {
            case 'messages':
                $query->where('is_message', true)->where('is_email', false);
                break;
            case 'emails':
                $query->where('is_email', true);
                break;
            case 'notifications':
                $query->where('is_notification', true);
                break;
            default:
                return redirect()->back()->with('error', 'Tipo di messaggio non valido.');
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhereHas('sender', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $items = $query->orderBy('created_at', 'desc')->get();
        return view("anthaleja.messages.{$type}.inbox", compact('items'));
    }

    public function show(Message $message)
    {
        // Verifica se il messaggio appartiene all'utente autenticato
        if (Auth::user()->character->id !== $message->recipient_id) {
            return redirect()->route('messages.inbox')->with('error', 'Non hai il permesso di visualizzare questo messaggio.');
        }

        // Segna il messaggio come letto
        $message->update(['status' => 'read']);

        // Ottieni tutti i destinatari disponibili per l'inoltro (ad esempio tutti i personaggi, tranne l'utente corrente)
        $recipients = Character::where('id', '!=', Auth::user()->character->id)->get();

        // Differenzia le viste in base al tipo di messaggio
        if ($message->is_email) {
            return view('anthaleja.messages.emails.show', compact('message', 'recipients'));
        } elseif ($message->is_message) {
            return view('anthaleja.messages.show', compact('message', 'recipients'));
        } elseif ($message->is_notification) {
            return view('anthaleja.messages.notifications.show', compact('message'));
        }

        return redirect()->route('messages.inbox')->with('error', 'Tipo di messaggio non riconosciuto.');
    }
    // Invia un nuovo messaggio
    public function send(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:characters,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::user()->character->id,
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_message' => true,
        ]);

        return redirect()->route('messages.inbox')->with('success', 'Messaggio inviato con successo.');
    }

    public function toggleReadStatus(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if (Auth::user()->character->id !== $message->recipient_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $newStatus = $request->input('status');
        $message->status = $newStatus;
        $message->save();

        return response()->json(['success' => true, 'newStatus' => $newStatus]);
    }

    public function markAsRead(Request $request)
    {
        $messageIds = $request->input('messages');

        // Filtra i messaggi dell'utente corrente e segna come letti
        Message::whereIn('id', $messageIds)
            ->where('recipient_id', Auth::user()->character->id)
            ->where(function ($query) use ($request) {
                // Se è presente il filtro per tipo, lo applichiamo
                if ($request->has('type')) {
                    if ($request->type == 'emails') {
                        $query->where('is_email', true);
                    } elseif ($request->type == 'notifications') {
                        $query->where('is_notification', true);
                    } elseif ($request->type == 'messages') {
                        $query->where('is_message', true)->where('is_email', false)->where('is_notification', false);
                    }
                }
            })
            ->update(['status' => 'read']);

        return redirect()->back()->with('success', 'Messaggi segnati come letti.');
    }


    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::user()->character->id,
            'recipient_id' => $message->sender_id, // Il destinatario è il mittente del messaggio originale
            'subject' => 'RE: ' . $message->subject,
            'message' => $request->reply_message,
            'is_message' => true,
        ]);

        return redirect()->route('messages.inbox')->with('success', 'Risposta inviata con successo.');
    }

    public function forward(Request $request, Message $message)
    {
        $request->validate([
            'recipient_id' => 'required|exists:characters,id',
        ]);

        Message::create([
            'sender_id' => Auth::user()->character->id,
            'recipient_id' => $request->recipient_id,
            'subject' => 'FWD: ' . $message->subject,
            'message' => $message->message,
            'is_message' => true,
        ]);

        return redirect()->route('messages.inbox')->with('success', 'Messaggio inoltrato con successo.');
    }

    public function checkNewNotifications()
    {
        $newNotificationsCount = Message::where('recipient_id', Auth::user()->character->id)
            ->where('is_notification', true)
            ->where('status', 'unread')
            ->count();

        return response()->json(['newNotifications' => $newNotificationsCount > 0, 'newNotificationsCount' => $newNotificationsCount]);
    }

    public function getNotifications()
    {
        $notifications = Message::where('recipient_id', Auth::user()->character->id)
            ->where('is_notification', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('anthaleja.messages.partials.notifications', compact('notifications'));
    }

    public function sendNotification($recipientId, $subject, $messageContent)
    {
        Message::create([
            'sender_id' => Auth::user()->character->id, // Può essere un NPC o sistema
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'message' => $messageContent,
            'is_message' => false,
            'is_notification' => true,
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,docx,txt', // Limite 10MB e formati accettati
        ]);

        $user = Auth::user()->character;
        $recipient = Character::where('email', $request->to)->firstOrFail();

        // Gestione degli allegati
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public'); // Salva nel filesystem pubblico
                $attachments[] = $path; // Aggiungi il percorso all'array degli allegati
            }
        }

        // Crea il messaggio
        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $recipient->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_email' => true, // Indica che è un'email
            'attachments' => !empty($attachments) ? json_encode($attachments) : null, // Salva gli allegati come JSON
        ]);

        return redirect()->route('messages.emailInbox')->with('success', 'Email inviata con successo.');
    }

    public function softDeleteSelected(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Cancella i messaggi selezionati con SoftDelete
        Message::whereIn('id', $request->messages)->delete();

        return redirect()->route('messages.inbox')->with('success', 'Messaggi cancellati con successo.');
    }

    public function restoreSelected(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Ripristina i messaggi cancellati con SoftDelete
        Message::withTrashed()->whereIn('id', $request->messages)->restore();

        return redirect()->route('messages.inbox')->with('success', 'Messaggi ripristinati con successo.');
    }
    public function forceDeleteSelected(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Cancellazione definitiva dei messaggi selezionati
        Message::withTrashed()->whereIn('id', $request->messages)->forceDelete();

        return redirect()->route('messages.inbox')->with('success', 'Messaggi eliminati definitivamente.');
    }

    public function archiveSelected(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Archivia i messaggi selezionati
        Message::whereIn('id', $request->messages)->update(['is_archived' => true]);

        return redirect()->route('messages.inbox')->with('success', 'Messaggi archiviati con successo.');
    }
    public function archivedMessages()
    {
        $user = Auth::user()->character;
        $archivedMessages = Message::where('recipient_id', $user->id)
            ->where('is_archived', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('messages.archived', compact('archivedMessages'));
    }
    public function restoreArchived(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Ripristina i messaggi archiviati
        Message::whereIn('id', $request->messages)->update(['is_archived' => false]);

        return redirect()->route('messages.archived')->with('success', 'Messaggi ripristinati con successo.');
    }
    public function forceDeleteArchived(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
        ]);

        // Cancellazione definitiva dei messaggi selezionati
        Message::withTrashed()->whereIn('id', $request->messages)->forceDelete();

        return redirect()->route('messages.archived')->with('success', 'Messaggi eliminati definitivamente.');
    }
    public function downloadAttachment(Message $message, $attachmentIndex)
    {
        $attachments = json_decode($message->attachments, true);

        if (isset($attachments[$attachmentIndex])) {
            $path = $attachments[$attachmentIndex];
            $filePath = storage_path("app/public/{$path}");

            if (file_exists($filePath)) {
                return response()->download($filePath);
            } else {
                dd('File non trovato.');
            }
        }

        dd('Allegato non trovato.');
    }

    public function deleteAttachment(Request $request, Message $message, $attachmentIndex)
    {
        $attachments = json_decode($message->attachments, true);

        if (isset($attachments[$attachmentIndex])) {
            $path = $attachments[$attachmentIndex];

            if (Storage::disk('public')->exists($path)) {
                // Elimina il file dal filesystem
                Storage::disk('public')->delete($path);
            }

            // Rimuovi l'allegato dall'array
            unset($attachments[$attachmentIndex]);

            // Aggiorna gli allegati nel database
            $message->update([
                'attachments' => !empty($attachments) ? json_encode(array_values($attachments)) : null,
            ]);

            return redirect()->route('messages.show', $message)->with('success', 'Allegato eliminato con successo.');
        }

        dd('Allegato non trovato.');
    }
}
