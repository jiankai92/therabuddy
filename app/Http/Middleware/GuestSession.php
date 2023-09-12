<?php

namespace App\Http\Middleware;

use App\Models\AiChat;
use App\Services\Chat\SessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestSession
{
    public function __construct(public SessionService $sessionService)
    {
        $this->sessionService = new SessionService();
    }
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
            if (isset($chat_model) && $this->sessionService->guestSessionExpired($chat_model)) {
                session()->regenerate();
            }
        }
        return $next($request);
    }
}
