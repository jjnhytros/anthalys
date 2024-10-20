<?php

namespace App\Http\Controllers\Anthaleja;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $character = Auth::user()->character;
        $notifications =
            Message::where('recipient_id', $character->id)
            ->where('is_notification', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('anthaleja.notifications.index', compact('notifications'));
    }

    public function checkNotifications()
    {
        $user = Auth::user()->character;
        $hasNotifications = Message::where('recipient_id', $user->id)
            ->where('is_notification', true)
            ->where('status', 'unread')
            ->exists();

        return response()->json(['hasNotifications' => $hasNotifications]);
    }

    public function markAsRead($id)
    {
        $notification = Message::findOrFail($id);
        if ($notification->recipient_id == Auth::user()->character->id) {
            $notification->update(['status' => 'read']);
        }
        return redirect()->route('notifications.index')->with('success', 'Notifica marcata come letta.');
    }

    public function archive($id)
    {
        $notification = Message::findOrFail($id);
        if ($notification->recipient_id == Auth::user()->character->id) {
            $notification->update(['is_archived' => true]);
        }
        return redirect()->route('notifications.index')->with('success', 'Notifica archiviata.');
    }

    public function destroy($id)
    {
        $notification = Message::findOrFail($id);
        if ($notification->recipient_id == Auth::user()->character->id) {
            $notification->delete(); // Soft Delete
        }
        return redirect()->route('notifications.index')->with('success', 'Notifica eliminata.');
    }

    public function restore($id)
    {
        $notification = Message::withTrashed()->findOrFail($id);
        if ($notification->recipient_id == Auth::user()->character->id) {
            $notification->restore();
        }
        return redirect()->route('notifications.index')->with('success', 'Notifica ripristinata.');
    }

    public function forceDelete($id)
    {
        $notification = Message::withTrashed()->findOrFail($id);
        if ($notification->recipient_id == Auth::user()->character->id) {
            $notification->forceDelete();
        }
        return redirect()->route('notifications.index')->with('success', 'Notifica eliminata definitivamente.');
    }

    public static function sendNotification($recipientId, $subject, $message, $url = null, $type = 'general')
    {
        $recipient = Character::find($recipientId);
        $profile = $recipient->profile;
        $preferences = $profile->preferences ?? [];

        // Verifica delle preferenze per ogni tipo di notifica
        switch ($type) {
            case 'new_follower':
                if (empty($preferences['new_follower_notification'])) return;
                break;
            case 'new_comment':
                if (empty($preferences['new_comment_notification'])) return;
                break;
            case 'new_message':
                if (empty($preferences['new_message_notification'])) return;
                break;
            case 'post_reactions':
                if (empty($preferences['post_reactions_notification'])) return;
                break;
            case 'job_offers':
                if (empty($preferences['job_offers_notification'])) return;
                break;
        }
        Message::create([
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'message' => $message,
            'url' => $url,
            'is_message' => false,
            'is_notification' => true,
            'status' => 'unread',
        ]);
    }
    public static function notifyNewFollower($recipientId, $followerId)
    {
        $recipient = Character::find($recipientId);
        $follower = Character::find($followerId);
        $profile = $recipient->profile;
        if (!empty($profile->preferences['new_follower_notification'])) {
            Message::create([
                'recipient_id' => $recipientId,
                'subject' => 'New Follower',
                'message' => "You have a new follower: {$follower->name}",
                'is_message' => false,
                'is_notification' => true,
                'status' => 'unread',
            ]);
        }
    }
    public static function notifyPostReaction($recipientId, $postId, $reactionType)
    {
        $recipient = Character::find($recipientId);
        $profile = $recipient->profile;
        if (!empty($profile->preferences['post_reactions_notification'])) {
            Message::create([
                'recipient_id' => $recipientId,
                'subject' => 'New Reaction to Your Post',
                'message' => "Someone reacted to your post with {$reactionType}.",
                'is_message' => false,
                'is_notification' => true,
                'status' => 'unread',
            ]);
        }
    }
}
