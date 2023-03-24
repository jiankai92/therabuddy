import './bootstrap';

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
        cacheDOM: function() {
            this.chatHistory = document.querySelector('.chat-history');
            this.button = document.querySelector('button');
            this.textarea = document.querySelector('#message-to-send');
            this.chatHistoryList = this.chatHistory.querySelector('ul');
        },
        bindEvents: function() {
            this.button.addEventListener('click', this.addMessage.bind(this));
            this.textarea.addEventListener('keyup', this.addMessageEnter.bind(this));
        },
        render: function() {
            this.scrollToBottom();
            if (this.messageToSend.trim() !== '') {
                var template = document.querySelector("#message-template").innerHTML;
                                // var messageTemplate = document.querySelector('#message-template').innerHTML;
                // var messageResponseTemplate = document.querySelector('#message-response-template').innerHTML;
                var context = {
                    messageOutput: this.messageToSend,
                    time: this.getCurrentTime()
                };
                var renderedTemplate = this.compileTemplate(template, context);

                this.chatHistoryList.insertAdjacentHTML('beforeend', renderedTemplate);
                this.scrollToBottom();
                this.textarea.value = '';

                // responses
                var templateResponse = document.querySelector("#message-response-template").innerHTML;
                var contextResponse = {
                    response: this.getRandomItem(this.messageResponses),
                    time: this.getCurrentTime()
                };
                var renderedResponseTemplate = this.compileTemplate(templateResponse, contextResponse);

                setTimeout(function() {
                    this.chatHistoryList.insertAdjacentHTML('beforeend', renderedResponseTemplate);
                    this.scrollToBottom();
                }.bind(this), 1500);
            }
        },
        compileTemplate: function(template, context) {
            return template.replace(/\${(.*?)}/g, function(match, p1) {
                return context[p1];
            });
        },
        addMessage: function() {
            this.messageToSend = this.textarea.value;
            this.render();
        },
        addMessageEnter: function(event) {
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
        }


    };

    chat.init();
})();
