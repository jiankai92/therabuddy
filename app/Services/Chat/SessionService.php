<?php

namespace App\Services\Chat;

use App\Models\AiChat;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class SessionService
{
    private int $warnExpireBuffer;

    const WARN_EXPIRE_BUFFER = 60;
    
    public function __construct()
    {
        $this->warnExpireBuffer = env('SESSION_LIFETIME') >= self::WARN_EXPIRE_BUFFER ? self::WARN_EXPIRE_BUFFER : intval(env('SESSION_LIFETIME')); // time remaining on session before warning shown
    }

    /**
     * @throws Exception
     */
    public function validate(AiChat $chat_model)
    {
        if (!Auth::user() && $chat_model->session_id !== session()->getId()) {
            throw new Exception('Invalid Session. Please refresh page and try again');
        }
    }

    public function timeToLive(AiChat $chat_model)
    {
        return (config('session.lifetime') - Carbon::now()->diffInMinutes($chat_model->created_at));
    }

    public function handleTimeoutWarning(AiChat $chat_model): void
    {
        $session_ttl = self::timeToLive($chat_model);

        if ($session_ttl <= $this->warnExpireBuffer) {
            $warn_message = 'Your session will expire in ' . $session_ttl . ' minutes. Please register an account to save your current chat history.';
            session()->now('warning', $warn_message);
        }
    }

    public function guestSessionExpired(AiChat $chat_model): bool
    {
        if (!Auth::user()) {
            return self::timeToLive($chat_model) <= 0;
        }
        return false;
    }
}