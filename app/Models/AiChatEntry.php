<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatEntry extends Model
{
    use HasFactory;

    const TYPE_PROMPT = 'prompt';
    const TYPE_RESPONSE = 'response';
    
    protected $table = 'ai_chat_entry';

    protected $hidden = ['created_at'];

    protected $fillable = ['chat_id', 'type', 'message'];
    
    public $timestamps = false;

    public function entries(): BelongsTo
    {
        return $this->belongsTo(AiChatEntry::class, 'chat_id');
    }
}
