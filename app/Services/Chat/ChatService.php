<?php


namespace App\Services\Chat;

use App\Models\AiChatEntry;
use App\Repositories\ChatRepository;
use App\Repositories\ChatEntryRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService
{
    protected OpenAiService $openAiService;
    protected ChatRepository $chatRepository;
    protected ChatEntryRepository $chatEntryRepository;
    protected SessionService $sessionService;

    public function __construct()
    {
        $this->openAiService = new OpenAiService();
        $this->chatRepository = new ChatRepository();
        $this->chatEntryRepository = new ChatEntryRepository();
        $this->sessionService = new SessionService();
    }

    /**
     * Stores user prompt, sends request to chat API endpoint, then stores and returns API response
     * @param string $message
     * @return string
     * @throws Exception
     */
    public function processMessageSubmission(string $message): string
    {
        try {
            if (Auth::check()) {
                $chat_model = $this->chatRepository->findOrCreateChatModel(['user_id' => Auth::id()]);
            } else {
                $chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
            }
            $this->sessionService->validate($chat_model);
            DB::beginTransaction();
            $this->chatEntryRepository->storeChatEntry($chat_model, ['message' => $message], AiChatEntry::TYPE_PROMPT);
            $chat_response = self::submitMessageToProviderAPI($message);
            $this->chatEntryRepository->storeChatEntry($chat_model, ['message' => $chat_response], AiChatEntry::TYPE_RESPONSE);
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

    /**
     * Process Non logged in user session. Session starts when user submits their first message
     * @param string $session_id
     * @return AiChat | null $chat_model 
     */
    public function processGuestSession(string $session_id): \App\Models\AiChat | null
    {
        $chat_model = $this->chatRepository->findBySession($session_id);
        if (!$chat_model) {
            return $chat_model;
        }
        $this->sessionService->handleTimeoutWarning($chat_model);
        return $chat_model;
    }
}