<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatEntry extends Model
{
    use HasFactory;
    
    protected $table = 'ai_chat_entry';

    protected $hidden = ['created_at'];

    protected $fillable = ['chat_id', 'type', 'message'];
    
    public $timestamps = false;
}
