<?php

namespace App\Http\Middleware;

use App\Models\AiChat;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestSession
{
    /**
     * Regenerate guest session if expired
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()) {
            $chat_model = AiChat::where('session_id', session()->getId())->first();
            if (isset($chat_model) && $chat_model->guestSessionExpired()) {
                session()->regenerate();
            }
        }
        return $next($request);
    }
}
