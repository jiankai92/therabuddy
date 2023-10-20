<?php

namespace App\Http\Controllers\Chat;

use App\Repositories\ChatRepository;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use App\Services\Ajax\AjaxResponseService;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected ChatService $chatService;
    protected ChatRepository $chatRepository;
    protected AjaxResponseService $ajaxResponseService;

    public function __construct()
    {
        $this->chatService = new ChatService();
        $this->chatRepository = new ChatRepository();
        $this->ajaxResponseService = new AjaxResponseService();
    }

    /**
     * Display the chat page along with chat history
     */
    public function index()
    {
        try {
            if (Auth::check()) {
                $chat_model = $this->chatRepository->findByUser(Auth::id());
            } else {
                $chat_model = $this->chatService->processGuestSession(session()->getId());
            }
            $chat_history = $chat_model?->entries ?? collect();
            return view('chat.index')->with('chat_history', $chat_history);
        } catch (Exception $ex) {
            writeToLog($ex->getFile(), $ex->getLine(), $ex->getMessage(), 'error', 'error');
            $chat_history = collect();
            return view('chat.index')->with('chat_history', $chat_history)->withErrors([$ex->getMessage()]);
        }
    }

    public function index2()
    {
        return view('chat.index2');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function textChatSubmitAjax(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $input = $request->all();
            $chat_response = $this->chatService->processMessageSubmission($input['message']);
            return $this->ajaxResponseService->setCode(200)->setBody($chat_response)->send();
        } catch (Exception $ex) {
            writeToLog($ex->getFile(), $ex->getLine(), $ex->getMessage(), 'error');
            return $this->ajaxResponseService->setError($ex->getMessage(), 500)->send();
        }
    }
    
}
