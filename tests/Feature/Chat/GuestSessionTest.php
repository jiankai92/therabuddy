<?php

namespace Tests\Feature\Chat;

use App\Repositories\ChatRepository;
use App\Services\Chat\SessionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestSessionTest extends TestCase
{
    use withFaker;

    private ChatRepository $chatRepository;
    private SessionService $sessionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->chatRepository = app()->make(ChatRepository::class);
        $this->sessionService = app()->make(SessionService::class);
    }

    public function test_session_regenerated_on_expiry(): void
    {
        // Create expired session
        $expired_chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        $expired_chat_model->created_at = Carbon::now()->subDays(2);
        $expired_chat_model->save();

        $response = $this->get('/chat');
        $response->assertStatus(200);
        $this->assertFalse($expired_chat_model->validSession());
    }
    /**
     * Test valid guest session validation
     */
    public function test_valid_guest_session(): void
    {
        $chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        $this->assertTrue($chat_model->validSession());
        
        $this->sessionService->validate($chat_model);
    }    
    /**
     * Test invalid guest session validation
     */
    public function test_expired_guest_session(): void
    {
        $expired_chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        session()->regenerate();
        $this->assertFalse($expired_chat_model->validSession());
        
        $this->expectException(\Exception::class);
        $this->sessionService->validate($expired_chat_model);
    }
    
}
