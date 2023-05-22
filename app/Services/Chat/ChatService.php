<?php


namespace App\Services\Chat;

use App\Models\AiChat;
use App\Models\AiChatEntry;
use App\Repositories\Chat\ChatRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ChatService
{
    protected OpenAiService $openAiService;
    protected ChatRepository $chatRepository;
    
    public function __construct()
    {
        $this->openAiService = new OpenAiService();
        $this->chatRepository = new ChatRepository();
    }
    
    /**
     * Stores user prompt, sends request to chat API endpoint, then stores and returns API response
     * @param string $message
     * @return mixed
     * @throws Exception
     */
    public function processMessageSubmission(string $message): mixed
    {
        try {
            DB::beginTransaction();
            $chat_model = self::getChatModel();
            self::storeChatEntry($chat_model, ['message' => $message], AiChatEntry::TYPE_PROMPT);
            $chat_response = $this->openAiService->submitMessageToChatApi($message);
            self::storeChatEntry($chat_model, ['message' => $chat_response], AiChatEntry::TYPE_RESPONSE);
            DB::commit();
            return $chat_response;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Test version of processMessageSubmission that simply returns a dummy response
     * @param string $message
     * @return mixed
     */
    public function testProcessMessageSubmission(string $message): mixed
    {
        return $this->openAiService->returnDummyResponseData($message);
    }

    /**
     * Finds users ai_chat entry or creates one if it doesn't exist
     * @return mixed
     * @throws Exception
     */
    private function getChatModel(): mixed
    {
        $model = $this->chatRepository->findOrCreateChatModel();
        if (!$model->exists()) {
            throw new Exception('Failed to find or create chat record');
        }
        return $model;
    }

    /**
     * Stores new ai_chat_entry
     * @param AiChat $chat_model
     * @param $input
     * @param $type
     * @throws Exception
     */
    private function storeChatEntry(AiChat $chat_model, $input, $type)
    {
        $chat_entry = $this->chatRepository->storeChatEntry($chat_model, $input, $type);
        if (!$chat_entry->exists()) {
            throw new Exception('Failed to store chat Entry');
        }
    }
}