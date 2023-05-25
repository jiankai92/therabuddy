<?php

namespace Tests\Feature\Chat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\AiChatEntry;
use App\Repositories\Chat\ChatRepository;
use App\Services\Chat\ChatService;
use Tests\TestCase;

class ProcessMessageTest extends TestCase
{
    use RefreshDatabase, withFaker;

    private mixed $chatRepository;
    private \PHPUnit\Framework\MockObject\MockObject|ChatService $chatServiceMock;
    private string $prompt_message;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatServiceMock = $this->createMock(ChatService::class);
        $this->chatServiceMock->method('submitMessageToProviderAPI')
            ->willReturn($this->faker->sentence);
        $this->chatRepository = app()->make(ChatRepository::class);
        $this->prompt_message = $this->faker->sentence;
    }

    /**
     * Test message prompt processing flow from submission to displaying response
     * @group requires-database
     */
    public function test_process_message_submit(): void
    {
        $chat_model = $this->chatRepository->findOrCreateChatModel();
        $this->assertTrue($chat_model->exists());

        $this->chatRepository->storeChatEntry($chat_model, ['message' => $this->prompt_message], AiChatEntry::TYPE_PROMPT);
        $this->assertDatabaseHas('ai_chat_entry', [
            'chat_id' => $chat_model->id,
            'type' => AiChatEntry::TYPE_PROMPT,
            'message' => $this->prompt_message,
        ]);

        $chat_response = $this->chatServiceMock->submitMessageToProviderAPI($this->prompt_message);
        $this->assertIsString($chat_response);

        $this->chatRepository->storeChatEntry($chat_model, ['message' => $chat_response], AiChatEntry::TYPE_RESPONSE);
        $this->assertDatabaseHas('ai_chat_entry', [
            'chat_id' => $chat_model->id,
            'type' => AiChatEntry::TYPE_RESPONSE,
            'message' => $chat_response,
        ]);
    }
}
