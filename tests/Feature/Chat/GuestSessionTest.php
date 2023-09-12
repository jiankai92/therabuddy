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

    /**
     * Test if new sessionId is generated when existing session entry exceeds 1 day old
     * @group requires-database
     */
    public function test_session_regenerated_on_expiry(): void
    {
        // Create expired session
        $expired_chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        $expired_chat_model->created_at = Carbon::now()->subDays(2);
        $expired_chat_model->save();

        $response = $this->get('/chat');
        $response->assertStatus(200);
        $this->expectException(\Exception::class);
        $this->assertFalse($this->sessionService->validate($expired_chat_model));
    }
    /**
     * Test valid guest session validation
     * @group requires-database
     */
    public function test_valid_guest_session(): void
    {
        $chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        $this->assertNull($this->sessionService->validate($chat_model));
    }    
    /**
     * Test expired guest session validation
     * @group requires-database
     */
    public function test_expired_guest_session(): void
    {
        $expired_chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        session()->regenerate();
        
        $this->expectException(\Exception::class);
        $this->sessionService->validate($expired_chat_model);
    }
    
}
