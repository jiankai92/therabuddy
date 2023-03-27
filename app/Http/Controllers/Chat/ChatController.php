<?php

namespace App\Http\Controllers\Chat;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
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

    public function textChatSubmitAjax(Request $request)
    {
        try {
            $input = $request->all();
            // handle $input['message']
            $chat_response = $this->chatService->submitMessageAndGetResponse($input['message']);
        } catch (Exception $ex) {
            //TODO: Handle error handling
            var_dump($ex->getMessage());
            dd('caught some error');
        }
        return $chat_response;
    }
    
}
