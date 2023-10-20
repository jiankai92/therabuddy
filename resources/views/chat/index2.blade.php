@extends('base')
@section('content')
    <div x-data="chatBot()" id="messages" class="flex flex-col space-y-4 p-3">
        <template x-for="(message, key) in messages">
            <div>
                <div class="flex items-end" :class="message.from=='bot'?'':'justify-end'">
                    <div class="flex flex-col space-y-2 text-md leading-tight max-w-lg mx-2"
                         :class="message.from=='bot'?'order-2 items-start':'order-1 items-end'">
                        <div>
                           <span class="px-4 py-3 rounded-xl inline-block"
                                 :class="message.from=='bot'?'rounded-bl-none bg-gray-100 text-gray-600':'rounded-br-none bg-blue-500 text-white'"
                                 x-html="message.text"></span>
                        </div>
                    </div>
                    <img :src="message.from=='bot'?'https://cdn.icon-icons.com/icons2/1371/PNG/512/robot02_90810.png':'https://i.pravatar.cc/100?img=7'"
                         alt="" class="w-6 h-6 rounded-full" :class="message.from=='bot'?'order-1':'order-2'">
                </div>
            </div>
        </template>
        <div x-show="botTyping" x-ref="typing">
            <div class="flex items-end">
                <div class="flex flex-col space-y-2 text-md leading-tight mx-2 order-2 items-start">
                    <div>
                        <img src="https://support.signal.org/hc/article_attachments/360016877511/typing-animation-3x.gif"
                             alt="..." class="w-16 ml-6"></div>
                </div>
            </div>
        </div>
        <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0 fixed w-full bottom-0 flex">
            <textarea name="message-to-send" id="message-to-send"
                      placeholder="Type your message"
                      class="w-full px-5 py-3.5 mb-2 resize-none rounded h-14 max-h-[240px]"
                      x-ref="chatBox"
                      @keydown.enter="chatBoxEnter($event)"
                      x-on:input="updateChatBoxHeight($event.target)"
            ></textarea>
            <div class="absolute items-center right-7 bottom-5 flex">
                <button type="button" x-ref="btnChatSubmit"
                        class="inline-flex items-center justify-center rounded h-8 w-8 transition duration-200 ease-in-out text-white bg-blue-500 hover:bg-blue-600 focus:outline-none"
                        @click.prevent="updateChat($refs.chatBox)">
                    <i class="fa fa-paper-plane -ml-0.5"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="h-28"></div>
@endsection

@section('scripts')
    <script>
        function chatBot() {
            return {
                botTyping: false,
                messages: [{
                    from: 'bot',
                    text: 'Hello world!'
                }],
                addChat: function (input, product) {
                    Promise.resolve(
                        this.messages.push({
                            from: 'user',
                            text: input
                        })
                    ).then(() => {
                        this.scrollToBottom();
                    });
                },
                handleInput: function (input) {
                    Promise.resolve(
                        this.messages.push({
                            from: 'user',
                            text: input
                        })
                    ).then(() => {
                        this.scrollToBottom();
                    });
                },
                handleOutput: function () {
                    this.botTyping = true
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });

                    setTimeout(() => {
                        this.botTyping = false;
                        this.messages.push({
                            from: 'bot',
                            text: 'hardcoded'
                        });
                        this.scrollToBottom();
                        this.enableChatBox();
                    }, (Math.floor(Math.random() * 2000) + 1500))
                },
                scrollToBottom: function () {
                    let chatHistory = document.getElementById("messages");
                    window.scrollTo({
                        top: chatHistory.scrollHeight,
                        behavior: 'smooth'
                    });
                },
                chatBoxEnter: function (event) {
                    if (event.keyCode === 13 && !event.shiftKey) {
                        // enter without shift prevent line break and enter message
                        event.preventDefault();
                        this.updateChat(event.target);
                    }
                },
                updateChat: function (target) {
                    if (this.botTyping === false && target.value.trim()) {
                        this.updateChatBoxHeight(target);
                        this.handleInput(target.value.trim())
                        target.value = '';
                        this.handleOutput();
                    }
                },
                updateChatBoxHeight: function (DOMChatBox, initialHeight = '56') {
                    DOMChatBox.style.height = initialHeight + 'px';
                    DOMChatBox.style.height = DOMChatBox.scrollHeight + 'px';
                }
            }
        }
    </script>
@endsection