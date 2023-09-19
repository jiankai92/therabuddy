<?php

namespace App\Repositories;

use App\Models\AiChat;
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
     * Finds ai_chat entry based on condition or creates one if it doesn't exist
     * Key pair value condition are session_id (non logged in) or user_id (logged in)
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

    public function assignUserIdToModel(AiChat $chat_model, $user_id): void
    {
        $chat_model->update(['user_id' => $user_id]);
    }
}