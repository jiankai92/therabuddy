<?php

namespace App\Http\Controllers\Chat;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use App\Services\Ajax\AjaxResponseService;
use App\Repositories\Chat\ChatRepository;

class ChatController extends Controller
{
    protected ChatService $chatService;
    protected AjaxResponseService $ajaxResponseService;
    protected ChatRepository $chatRepository;

    public function __construct()
    {
        $this->chatService = new ChatService();
        $this->ajaxResponseService = new AjaxResponseService();
        $this->chatRepository = new ChatRepository();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('chat.index');
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
            $this->chatService->storeChat($input, $this->chatRepository::TYPE_PROMPT);
            $chat_response = $this->chatService->submitMessageAndGetResponse($input['message']);
            $this->chatService->storeChat(['message' => $chat_response], $this->chatRepository::TYPE_RESPONSE);
            return $this->ajaxResponseService->setCode(200)->setBody($chat_response)->send();
        } catch (Exception $ex) {
            return $this->ajaxResponseService->setError($ex->getMessage(), 500)->send();
        }
    }
    
}
