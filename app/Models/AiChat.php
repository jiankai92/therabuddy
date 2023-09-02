<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class AiChat extends Model
{
    use HasFactory;

    protected $table = 'ai_chat';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['user_id', 'session_id'];

    public function entries(): HasMany
    {
        return $this->hasMany(AiChatEntry::class, 'chat_id');
    }

    public function guestSessionTtl(): int
    {
        return (config('session.lifetime') - Carbon::now()->diffInMinutes($this->created_at));
    }

    public function guestSessionExpired(): bool
    {
        return $this->guestSessionTtl() <= 0;
    }

    public function validSession(): bool
    {
        if (!Auth::user()) {
            return $this->session_id == session()->getId();
        }
        return true;
    }
}
