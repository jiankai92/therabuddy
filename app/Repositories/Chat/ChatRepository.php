<?php

namespace App\Repositories\Chat;

use App\Models\AiChat;
use App\Models\AiChatEntry;

class ChatRepository
{
    public function storeMessageEntryAsUser()
    {
        // Check session or user id
        
        // find associated chat
        
        // if found then store in messages column
        // else create entry
    }    
    public function storeMessageEntryAsGuest()
    {
        // Check session or user id
        
        // find associated chat
        
        // if found then store in messages column
        // else create entry
    }

    public function findOrCreateChatModel()
    {
        return AiChat::firstOrCreate(
        // TODO: configure this to check user ID , followed by session ID
            ['id' => 1],
            // TODO: create with session ID or userID
            []
        );
    }

    public function storeChatEntry(AiChat $chat_model, $input, $type = null)
    {
        $chat_entry_data = [
            'chat_id' => $chat_model->id,
            'type' => $type,
            'message' => $input['message'],
        ];
        return AiChatEntry::create($chat_entry_data);
    }
}