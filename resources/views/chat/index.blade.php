@extends('base')
@section('content')
    <div class="mx-auto my-0 rounded clearfix">
        <div class="bg-slate-100">
            <div class="chat-history py-7 px-5">
                <div class="h-28"></div>
                <ul>
                    @include('chat.partials.response-bubble', [
                        'time' => '',
                        'message' => 'Hi there, I\'m Therabuddy and I\'m always here if you need someone to express yourself to &#x1f60a;'
                    ])
                    @foreach($chat_history as $entry)
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
                <div class="h-36"></div>
            </div> <!-- end chat-history -->
            <div class="chat-message clearfix p-7 fixed w-full bottom-0 bg-slate-100">
            <textarea name="message-to-send" id="message-to-send"
                      placeholder="Type your message" rows="3"
                      class="w-full px-5 py-2 text-sm mb-2 rounded"></textarea>
                <i class="fa fa-file-o"></i>
                <i class="fa fa-file-image-o"></i>

                <span class="chat-error text-red-700">&nbsp;</span>
                <button id="btn-send-msg" class="float-right border-0 uppercase text-base font-bold cursor-pointer">
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

    <script id="response-template" type="text/template">
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
                    this.sendButton = document.querySelector('#btn-send-msg');
                    this.chatBox = document.querySelector('#message-to-send');
                    this.chatHistoryList = this.chatHistory.querySelector('ul');
                },
                bindEvents: function () {
                    this.sendButton.addEventListener('click', this.addMessage.bind(this));
                    this.chatBox.addEventListener('keydown', this.addMessageEnter.bind(this));
                },
                render: async function () {
                    this.scrollToBottom();
                    if (this.messageToSend.trim() !== '') {
                        this.disableChatBox(true);
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
                        this.chatBox.value = '';

                        // responses
                        try {
                            let chatResponse = await this.sendAndReceiveChatResponse(this.messageToSend);
                            if (chatResponse.data.code === 200) {
                                let templateResponse = document.querySelector("#response-template").innerHTML;
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
                            this.disableChatBox(false);
                        } catch (e) {
                            this.toggleErrorState(true);
                            this.disableChatBox(false);
                        }
                    }
                },
                compileTemplate: function (template, context) {
                    return template.replace(/\${(.*?)}/g, function (match, p1) {
                        return context[p1];
                    });
                },
                addMessage: function () {
                    this.messageToSend = this.chatBox.value;
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
                    let chatHistory = document.querySelector('.chat-history');
                    window.scrollTo({
                        top: chatHistory.scrollHeight,
                        behavior: 'smooth'
                    });
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
                },
                disableChatBox: function (disable) {
                    if (disable) {
                        this.sendButton.disabled = true;
                        this.chatBox.disabled = true;
                    } else {
                        this.sendButton.disabled = false;
                        this.chatBox.disabled = false;
                    }
                }
            };

            chat.init();
        })();
    </script>
@endsection

