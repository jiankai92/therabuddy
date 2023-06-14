@extends('base')
@section('content')
    <div class="mx-auto my-0 rounded clearfix">
        <div class="bg-slate-100">
            <div class="chat-history py-7 px-5">
                <ul>
                    @include('chat.partials.message-bubble', [
                        'time' => '10:10 AM, Today',
                        'message' => 'Hi Vincent, how are you? How is the project coming along?'
                    ])
                    @include('chat.partials.response-bubble', [
                        'time' => '10:12 AM, Today',
                        'message' => 'Are we meeting today? Project has been already finished and I have results to show you.'
                    ])
                    @foreach($chat_history as $entry)
                        {{--                    @if($loop->last)
                                            @dd(\Carbon\Carbon::parse($entry->created_at)->format('Y-m-d H:i:s.v'),\Carbon\Carbon::parse($entry->created_at)->diffForHumans())
                                            @endif--}}
                        @switch($entry->type)
                            @case(\App\Models\AiChatEntry::TYPE_PROMPT)
                            @include('chat.partials.message-bubble', [
                                'time' => \Carbon\Carbon::parse($entry->created_at)->diffForHumans(),
                                'message' => $entry->message
                            ])
                            @break
                            @case(\App\Models\AiChatEntry::TYPE_RESPONSE)
                            @include('chat.partials.response-bubble', [
                                'time' => \Carbon\Carbon::parse($entry->created_at)->diffForHumans(),
                                'message' => $entry->message
                            ])
                            @default
                        @endswitch
                    @endforeach
                </ul>
            </div> <!-- end chat-history -->
            <div class="chat-message clearfix p-7">
            <textarea name="message-to-send" id="message-to-send"
                      placeholder="Type your message" rows="3"
                      class="w-full px-5 py-2 text-sm mb-2 rounded"></textarea>
                <i class="fa fa-file-o"></i>
                <i class="fa fa-file-image-o"></i>

                <span class="chat-error text-red-700">&nbsp;</span>
                <button class="float-right border-0 uppercase text-base font-bold cursor-pointer">
                    <label class="cursor-pointer" for="message-to-send">Send</label>
                </button>

            </div> <!-- end chat-message -->

        </div> <!-- end chat -->

    </div> <!-- end container -->
@endsection

@section('scripts')
    <script id="message-template" type="text/template">
@include('chat.partials.message-bubble', [
    'time' => '${time}',
    'message' => '${messageOutput}'
])
    </script>

    <script id="message-response-template" type="text/template">
@include('chat.partials.response-bubble', [
    'time' => '${time}',
    'message' => '${response}'
])
    </script>
    <script type="module">
        (function () {
            let chat = {
                messageToSend: '',
                init: function () {
                    this.cacheDOM();
                    this.bindEvents();
                    this.render();
                },
                cacheDOM: function () {
                    this.chatHistory = document.querySelector('.chat-history');
                    this.button = document.querySelector('button');
                    this.textarea = document.querySelector('#message-to-send');
                    this.chatHistoryList = this.chatHistory.querySelector('ul');
                },
                bindEvents: function () {
                    this.button.addEventListener('click', this.addMessage.bind(this));
                    this.textarea.addEventListener('keydown', this.addMessageEnter.bind(this));
                },
                render: async function () {
                    this.scrollToBottom();
                    if (this.messageToSend.trim() !== '') {
                        this.toggleErrorState(false);
                        // Get message content
                        let template = document.querySelector("#message-template").innerHTML;
                        let context = {
                            messageOutput: this.messageToSend,
                            time: '{{ \Carbon\Carbon::now()->diffForHumans() }}'
                        };
                        // Populate sender message and reset 
                        let renderedTemplate = this.compileTemplate(template, context);
                        this.chatHistoryList.insertAdjacentHTML('beforeend', renderedTemplate);
                        this.scrollToBottom();
                        this.textarea.value = '';

                        // responses
                        try {
                            let chatResponse = await this.sendAndReceiveChatResponse(this.messageToSend);
                            if (chatResponse.data.code === 200) {
                                let templateResponse = document.querySelector("#message-response-template").innerHTML;
                                let contextResponse = {
                                    response: chatResponse.data.body,
                                    time: '{{ \Carbon\Carbon::now()->diffForHumans() }}'
                                };
                                let renderedResponseTemplate = this.compileTemplate(templateResponse, contextResponse);
                                this.chatHistoryList.insertAdjacentHTML('beforeend', renderedResponseTemplate);
                                this.scrollToBottom();
                            } else {
                                this.toggleErrorState(true);
                            }
                        } catch (e) {
                            this.toggleErrorState(true);
                        }
                    }
                },
                compileTemplate: function (template, context) {
                    return template.replace(/\${(.*?)}/g, function (match, p1) {
                        return context[p1];
                    });
                },
                addMessage: function () {
                    this.messageToSend = this.textarea.value;
                    this.render();
                },
                addMessageEnter: function (event) {
                    if (event.keyCode === 13 && !event.shiftKey) {
                        // enter without shift prevent line break and enter message
                        event.preventDefault();
                        this.addMessage();
                    }
                },
                scrollToBottom: function () {
                    var chatHistory = document.querySelector('.chat-history');
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                },
                sendAndReceiveChatResponse: async function (message) {
                    return await axios.post('{{route('text-chat-submit')}}', {message: message})
                },
                toggleErrorState: function (hasError) {
                    let errorMessage = document.querySelector("span.chat-error");
                    let chatBox = document.querySelector("#message-to-send");
                    if (hasError) {
                        errorMessage.innerHTML = 'Error Generating response, please try again';
                        chatBox.classList.add(
                            "focus:ring-0",
                            "border",
                            "border-solid",
                            "border-red-700",
                            "focus:border",
                            "focus:border-solid",
                            "focus:border-red-700",
                            "focus-visible:border",
                            "focus-visible:border-solid",
                            "focus-visible:border-red-700",
                        );
                    } else {
                        errorMessage.innerHTML = ''
                        chatBox.classList.remove(
                            "focus:ring-0",
                            "border",
                            "border-solid",
                            "border-red-700",
                            "focus:border",
                            "focus:border-solid",
                            "focus:border-red-700",
                            "focus-visible:border",
                            "focus-visible:border-solid",
                            "focus-visible:border-red-700",
                        );
                    }
                }
            };

            chat.init();
        })();
    </script>
@endsection

