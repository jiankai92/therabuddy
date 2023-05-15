<?php


namespace App\Services\Chat;

use App\Repositories\Chat\ChatRepository;

class ChatService
{
    protected OpenAiService $openAiService;
    protected ChatRepository $chatRepository;
    
    public function __construct()
    {
        $this->openAiService = new OpenAiService();
        $this->chatRepository = new ChatRepository();
    }
    
    public function submitMessageAndGetResponse(string $message)
    {
//        return $this->openAiService->returnDummyResponseData($message);
        return $this->openAiService->submitMessageToChatApi($message);
    }

    public function storeChat($input, $type)
    {
        $chat = $this->chatRepository->findOrCreateChatModel();
        if (!$chat->exists()) {
            throw new \Exception('Failed to find or create chat record');
        }
        $chat_entry = $this->chatRepository->storeChatEntry($chat, $input,$type);
        if (!$chat_entry->exists()) {
            throw new \Exception('Failed to store chat Entry');
        }
    }
}