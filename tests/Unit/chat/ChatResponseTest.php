<?php

namespace Tests\Unit\chat;

use App\Services\Chat\ChatService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatResponseTest extends TestCase
{
    use withFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test to see if the if the output of chat response is string
     * @group external-services
     */
    public function testChatResponseFormat(): void
    {
        $chat_service = app()->make(ChatService::class);
        $chat_message = $this->faker->sentence;
        $this->assertIsString($chat_service->submitMessageAndGetResponse($chat_message));
    }
}
