<?php


namespace App\Services\Chat;

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
            $chat_model = $this->chatRepository->findOrCreateChatModel();
            $this->chatRepository->storeChatEntry($chat_model, ['message' => $message], AiChatEntry::TYPE_PROMPT);
            $chat_response = self::submitMessageToProviderAPI($message);
            $this->chatRepository->storeChatEntry($chat_model, ['message' => $chat_response], AiChatEntry::TYPE_RESPONSE);
            DB::commit();
            return $chat_response;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Sends prompt to provider API endpoint and receive response
     * @param $message
     * @return string
     */
    public function submitMessageToProviderAPI($message): string
    {
        return $this->openAiService->submitMessageToChatApi($message);
    }

    /**
     * Test version of processMessageSubmission that simply returns a dummy response
     * @param string $message
     * @return string
     */
    public function testProcessMessageSubmission(string $message): string
    {
        return $this->openAiService->returnDummyResponseData($message);
    }
}