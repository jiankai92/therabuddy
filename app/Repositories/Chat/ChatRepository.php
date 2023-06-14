<?php

namespace App\Repositories\Chat;

use App\Models\AiChat;
use App\Models\AiChatEntry;
use Exception;

class ChatRepository
{
    public function findByUser(string $user_id)
    {
        return AiChat::where('user_id', $user_id)->first();
    }

    public function findBySession(string $session_id)
    {
        return AiChat::where('session_id', $session_id)->first();
    }

    /**
     * Finds users ai_chat entry or creates one if it doesn't exist
     * @param array $condition
     * @return mixed
     * @throws Exception
     */
    public function findOrCreateChatModel(array $condition): mixed
    {
        $model = AiChat::firstOrCreate(
            $condition,
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