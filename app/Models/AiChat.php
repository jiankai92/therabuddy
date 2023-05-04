<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChat extends Model
{
    use HasFactory;

    protected $table = 'ai_chat';

    protected $hidden = ['created_at'];

    protected $fillable = ['user_id', 'session_id'];
}
