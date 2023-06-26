<?php


namespace App\Services\Chat;

use App\Http\Resources\Chat\ChatDummyResponseResource;
use GuzzleHttp\Client;
use App\Http\Resources\Chat\ChatRequestResource;
use Illuminate\Support\Facades\Log;

class OpenAiService
{
    const MODELS = [
        'GPT-4' => 'gpt-4',
        'GPT-4-0314' => 'gpt-4-0314',
        'GPT-4-32K' => 'gpt-4-32k',
        'GPT-4-32K-0314' => 'gpt-4-32k-0314',
        'GPT-3.5-TURBO' => 'gpt-3.5-turbo',
        'GPT-3.5-TURBO-0301' => 'gpt-3.5-turbo-0301'
    ];
    const CHAT_COMPLETION = [
        'endpoint' => 'chat/completions',
        'models' => [
            self::MODELS['GPT-4'],
            self::MODELS['GPT-4-0314'],
            self::MODELS['GPT-4-32K'],
            self::MODELS['GPT-4-32K-0314'],
            self::MODELS['GPT-3.5-TURBO'],
            self::MODELS['GPT-3.5-TURBO-0301'],
        ],
        'default_model' => self::MODELS['GPT-3.5-TURBO']
    ];
    private string $openAIUrl;

    public function __construct()
    {
        $this->openAIUrl = config('services.openai.host') . '/' . config('services.openai.version') . '/';
    }

    public function submitMessageToChatApi(string $message)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.openai.key')
            ]
        ]);
        $endpoint = $this->openAIUrl . self::CHAT_COMPLETION['endpoint'];
        $request_data = [
            'model' => self::CHAT_COMPLETION['default_model'],
            'messages' => [
                "role" => "user",
                "content" => $message
            ],
        ];
        $request_body = collect(new ChatRequestResource($request_data))->toJson();
        writeToLog(__FILE__, __LINE__, "Outgoing: \n" . $request_body, 'notice', 'openai');
        $response = $client->post($endpoint, [
            'body' => "$request_body"
        ]);
        $response_content = $response->getBody()->getContents();
        writeToLog(__FILE__, __LINE__, "Incoming: \n" . $response_content, 'notice', 'openai');
        $response_content = json_decode($response_content, true);
        return $response_content['choices'][0]['message']['content'];
    }

    public function returnDummyResponseData(string $message)
    {
        $response = collect(new ChatDummyResponseResource($message))->toArray();
        Log::notice('Incoming: ' . print_r($response, 1));
        return $response['choices'][0]['message']['content'];
    }
}