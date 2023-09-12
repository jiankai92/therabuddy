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
}
