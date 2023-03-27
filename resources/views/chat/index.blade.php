@extends('base')
<div class="container clearfix">
    <div class="chat">
        <div class="chat-header clearfix">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar"/>

            <div class="chat-about">
                <div class="chat-with">Chat with Vincent Porter</div>
                <div class="chat-num-messages">already 1 902 messages</div>
            </div>
            <i class="fa fa-star"></i>
        </div> <!-- end chat-header -->

        <div class="chat-history">
            <ul>
                <li class="clearfix">
                    <div class="message-data align-right">
                        <span class="message-data-time">10:10 AM, Today</span> &nbsp; &nbsp;
                        <span class="message-data-name">Olia</span> <i class="fa fa-circle me"></i>

                    </div>
                    <div class="message other-message float-right">
                        Hi Vincent, how are you? How is the project coming along?
                    </div>
                </li>

                <li>
                    <div class="message-data">
                        <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                        <span class="message-data-time">10:12 AM, Today</span>
                    </div>
                    <div class="message my-message">
                        Are we meeting today? Project has been already finished and I have results to show you.
                    </div>
                </li>

                <li class="clearfix">
                    <div class="message-data align-right">
                        <span class="message-data-time">10:14 AM, Today</span> &nbsp; &nbsp;
                        <span class="message-data-name">Olia</span> <i class="fa fa-circle me"></i>

                    </div>
                    <div class="message other-message float-right">
                        Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced
                        any problems at the last phase of the project?
                    </div>
                </li>

                <li>
                    <div class="message-data">
                        <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                        <span class="message-data-time">10:20 AM, Today</span>
                    </div>
                    <div class="message my-message">
                        Actually everything was fine. I'm very excited to show this to our team.
                    </div>
                </li>

            </ul>

        </div> <!-- end chat-history -->

        <div class="chat-message clearfix">
            <label for="message-to-send"></label><textarea name="message-to-send" id="message-to-send"
                                                           placeholder="Type your message" rows="3"></textarea>

            <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
            <i class="fa fa-file-image-o"></i>

            <button>Send</button>

        </div> <!-- end chat-message -->

    </div> <!-- end chat -->

</div> <!-- end container -->

<script id="message-template" type="text/template">
    <li class="clearfix">
        <div class="message-data align-right">
            <span class="message-data-time">${time}, Today</span> &nbsp; &nbsp;
            <span class="message-data-name">Olia</span> <i class="fa fa-circle me"></i>
        </div>
        <div class="message other-message float-right">
            ${messageOutput}
        </div>
    </li>
</script>

<script id="message-response-template" type="text/template">
    <li>
        <div class="message-data">
            <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
            <span class="message-data-time">${time}, Today</span>
        </div>
        <div class="message my-message">
            ${response}
        </div>
    </li>
</script>
<script type="module">
    (function () {
        var chat = {
            messageToSend: '',
            messageResponses: [
                'Why did the web developer leave the restaurant? Because of the table layout.',
                'How do you comfort a JavaScript bug? You console it.',
                'An SQL query enters a bar, approaches two tables and asks: "May I join you?"',
                'What is the most used language in programming? Profanity.',
                'What is the object-oriented way to become wealthy? Inheritance.',
                'An SEO expert walks into a bar, bars, pub, tavern, public house, Irish pub, drinks, beer, alcohol'
            ],
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
                this.textarea.addEventListener('keyup', this.addMessageEnter.bind(this));
            },
            render: async function () {
                this.scrollToBottom();
                if (this.messageToSend.trim() !== '') {
                    var template = document.querySelector("#message-template").innerHTML;
                    var context = {
                        messageOutput: this.messageToSend,
                        time: this.getCurrentTime()
                    };
                    var renderedTemplate = this.compileTemplate(template, context);

                    this.chatHistoryList.insertAdjacentHTML('beforeend', renderedTemplate);
                    this.scrollToBottom();
                    this.textarea.value = '';

                    // responses
                    // TODO: Hit API here
                    let chatResponse = await this.sendAndReceiveChatResponse(this.messageToSend)
                    var templateResponse = document.querySelector("#message-response-template").innerHTML;
                    var contextResponse = {
                        response: chatResponse.data,
                        // response: this.getRandomItem(this.messageResponses),
                        time: this.getCurrentTime()
                    };
                    var renderedResponseTemplate = this.compileTemplate(templateResponse, contextResponse);
                    this.chatHistoryList.insertAdjacentHTML('beforeend', renderedResponseTemplate);
                    this.scrollToBottom();
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
                // enter was pressed
                if (event.keyCode === 13) {
                    this.addMessage();
                }
            },
            scrollToBottom: function () {
                var chatHistory = document.querySelector('.chat-history');
                chatHistory.scrollTop = chatHistory.scrollHeight;
            },
            getCurrentTime: function () {
                return new Date().toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
            },
            getRandomItem: function (arr) {
                return arr[Math.floor(Math.random() * arr.length)];
            },
            sendAndReceiveChatResponse: async function (message) {
                return await axios.post('{{route('text-chat-submit')}}', {message: message})
            }
        };

        chat.init();
    })();
</script>

