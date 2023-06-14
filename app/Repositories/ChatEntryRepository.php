<?php

namespace App\Repositories;

use App\Models\AiChat;
use App\Models\AiChatEntry;
use Exception;

class ChatEntryRepository
{
    /**
     * Stores new ai_chat_entry
     * @param AiChat $chat_model
     * @param $input
     * @param null $type
     * @throws Exception
     */
    public function storeChatEntry(AiChat $chat_model, $input, $type = null)
    {
        $chat_entry_data = [
            'chat_id' => $chat_model->id,
            'type' => $type,
            'message' => $input['message'],
        ];
        $chat_entry = AiChatEntry::create($chat_entry_data);
        if (!$chat_entry->exists()) {
            throw new Exception('Failed to store chat Entry');
        }
    }
}