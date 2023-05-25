<?php

namespace App\Repositories\Chat;

use App\Models\AiChat;
use App\Models\AiChatEntry;
use Exception;

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

    /**
     * Finds users ai_chat entry or creates one if it doesn't exist
     * @return mixed
     * @throws Exception
     */
    public function findOrCreateChatModel(): mixed
    {
        $model = AiChat::firstOrCreate(
        // TODO: configure this to check user ID , followed by session ID
            ['id' => 1],
            // TODO: create with session ID or userID
            []
        );
        if (!$model->exists()) {
            throw new Exception('Failed to find or create chat record');
        }
        return $model;
    }

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