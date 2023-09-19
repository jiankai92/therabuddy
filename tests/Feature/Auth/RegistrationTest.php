<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\RegisterAccountRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\ChatRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private ChatRepository $chatRepository;
    private UserService $userService;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->chatRepository = app()->make(ChatRepository::class);
        $this->userService = app()->make(UserService::class);
    }
    
    /**
     * @group requires-database
     */
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * @group requires-database
     */
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::CHAT);
    }

    /**
     * @group requires-database
     */
    public function test_session_chat_saved_on_registration(): void
    {
        $chat_model = $this->chatRepository->findOrCreateChatModel(['session_id' => session()->getId()]);
        $request = new RegisterAccountRequest(
            [
                'name' => 'Test Save Chat',
                'email' => 'testsavechat@example.com',
                'password' => 'password',
            ]
        );
        $user = $this->userService->handleRegisterAccount($request);
        $chat_model->refresh();
        $this->assertEquals(
            $chat_model->user_id,
            $user->id
        );
    }
}
