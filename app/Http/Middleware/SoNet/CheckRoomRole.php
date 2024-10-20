<?php

namespace App\Http\Middleware\SoNet;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetRoomMember;

class CheckRoomRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredRole = 2)
    {
        // $roomId = $request->route('room')->id;
        // $characterId = Auth::user()->character->id;

        // $member = SonetRoomMember::where('room_id', $roomId)
        //     ->where('character_id', $characterId)
        //     ->first();

        // if (!$member || !$this->hasRole($member->role, $requiredRole)) {
        //     return redirect()->route('rooms.index')->withErrors('Non hai i permessi per accedere a questa funzione.');
        // }

        return $next($request);
    }

    private function hasRole($userRole, $requiredRole)
    {
        $rolesHierarchy = ['member' => 0, 'moderator' => 1, 'admin' => 2];
        return $rolesHierarchy[$userRole] >= $rolesHierarchy[$requiredRole];
    }
}
