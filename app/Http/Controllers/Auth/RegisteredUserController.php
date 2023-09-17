<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAccountRequest;
use App\Providers\RouteServiceProvider;
use App\Services\Ajax\AjaxResponseService;
use App\Services\Chat\ChatService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected ChatService $chatService,
        protected UserService $userService,
        protected AjaxResponseService $ajaxResponseService
    ){}
    
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterAccountRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->handleRegisterAccount($request);
            return redirect(RouteServiceProvider::CHAT)->with('success', 'Account Created successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function ajaxStore(RegisterAccountRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->userService->handleRegisterAccount($request);
            return $this->ajaxResponseService->setCode(200)->setBody(
                ['success', 'Account Created successfully']
            )->send();
        } catch (\Exception $ex) {
            return $this->ajaxResponseService->setError($ex->getMessage(), 500)->send();
        }
    }

    public function ajaxRedirect(): RedirectResponse
    {
        if (Auth::user()) {
            return redirect(RouteServiceProvider::CHAT)->with('success', 'User Registered Successfully');
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }
}
