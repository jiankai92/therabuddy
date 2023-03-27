<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatDummyResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => "chatcmpl-6xo7punLYLPB02l7rezN6VE40T9QH",
            "object" => "chat.completion",
            "created" => 1679712053,
            "model" => "gpt-3.5-turbo-0301",
            "usage" => [
                "prompt_tokens" => 14,
                "completion_tokens" => 5,
                "total_tokens" => 19
            ],
            "choices" => [
                [
                    "message" => [
                        "role" => "assistant",
                        "content" => "This is a test!"
                    ],
                    "finish_reason" => "stop",
                    "index" => 0
                ]
            ]
        ];
    }
}
