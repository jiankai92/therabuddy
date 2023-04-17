<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatModel extends Model
{
    use HasFactory;

    protected $table = 'ai_chat';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['user_id', 'session_id', 'messages'];
}
