<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiChat extends Model
{
    use HasFactory;

    protected $table = 'ai_chat';

    protected $hidden = ['created_at'];

    protected $fillable = ['user_id', 'session_id'];

    public $timestamps = false;

    public function entries(): HasMany
    {
        return $this->hasMany(AiChatEntry::class, 'chat_id');
    }
}
