<?php


namespace App\Services\Chat;

//use App\Services\Chat\OpenAiService;

class ChatService
{
    protected OpenAiService $openAiService;
    
    public function __construct()
    {
        $this->openAiService = new OpenAiService();
    }
    
    public function submitMessageAndGetResponse(string $message)
    {
//        return $this->openAiService->returnDummyResponseData($message);
        return $this->openAiService->submitMessageToChatApi($message);
    }

}