<?php

namespace App\Services;

use App\Http\Requests\Auth\RegisterAccountRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Chat\ChatService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected ChatService $chatService,
    ){}

    /**
     * Handles register account request
     * @param RegisterAccountRequest $request
     * @return User|\Exception
     * @throws \Exception
     */
    public function handleRegisterAccount(RegisterAccountRequest $request): User|\Exception
    {
        try {
            $input = $request->all();
            DB::beginTransaction();
            $user = $this->userRepository->createUser($input['name'], $input['email'], $input['password']);
            $this->chatService->storeSessionChatHistory($user->id, session()->getId());
            event(new Registered($user));
            Auth::login($user);
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();
            writeToLog($ex->getFile(), $ex->getLine(), $ex->getMessage(), 'error');
            throw new \Exception('Failed to Register Account');
        }
    }
}