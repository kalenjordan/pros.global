<template>
    <div class="chat">
        <beautiful-chat
                :participants="participants"
                :titleImageUrl="titleImageUrl"
                :onMessageWasSent="onMessageWasSent"
                :messageList="messageList"
                :newMessagesCount="newMessagesCount"
                :isOpen="isChatOpen"
                :close="closeChat"
                :open="openChat"
                :showEmoji="true"
                :showFile="true"
                :showTypingIndicator="showTypingIndicator"
                :alwaysScrollToBottom="alwaysScrollToBottom"
                :messageStyling="messageStyling"></beautiful-chat>
    </div>
</template>

<script>
    export default {
        props: ['user', 'isOpen'],
        data() {
            return {
                participants: [
                    {
                        id: 'otheruser',
                        name: 'Placeholder',
                        imageUrl: 'https://avatars3.githubusercontent.com/u/1915989?s=230&v=4'
                    },
                ],
                titleImageUrl: 'https://a.slack-edge.com/66f9/img/avatars-teams/ava_0001-34.png',
                messageList: [],
                newMessagesCount: 0,
                isChatOpen: false, // to determine whether the chat window should be open or closed
                showTypingIndicator: '', // when set to a value matching the participant.id it shows the typing indicator for the specific user
                alwaysScrollToBottom: true,
                messageStyling: false,
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeys);
            window.Events.$on('clicked-chat-notification', () => {
                this.openChat();
            });

            // If I use the property on the chat component then the rest of the stuff
            // that gts initializes in openChat() isn't initialized
            if (this.isOpen) {
                this.openChat();
            }
        },

        methods: {
            hotkeys(e) {
                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'c') {
                        this.openChat();
                    } else if (e.key === 'Escape') {
                        this.closeChat();
                    }
                }
            },
            sendMessage (text) {
                if (text.length > 0) {
                    this.newMessagesCount = this.isChatOpen ? this.newMessagesCount : this.newMessagesCount + 1
                    this.onMessageWasSent({ author: 'support', type: 'text', data: { text } })
                }
            },
            onMessageWasSent (message) {
                axios.post(this.api('messages'), {
                    message: message.data.text,
                    to_user_id: this.user.id,
                }).then((response) => {
                    this.messageList = [ ...this.messageList, message ]
                });
            },
            openChat () {
                if (! this.loggedIn) {
                    alert("Please login before you can message " + this.user.name);
                    return;
                }

                // called when the user clicks on the fab button to open the chat
                this.isChatOpen = true;
                this.newMessagesCount = 0;
                this.participants = [
                    {
                        id: this.user.id,
                        name: this.user.name,
                        imageUrl: this.user.avatar_path,
                    },
                ];
                this.titleImageUrl = this.user.avatar_path;
                this.listenForMessages();

                if (this.messageList.length === 0) {
                    let auth = '?api_token=' + this.loggedInUser.api_token;
                    axios.get(this.api('messages/with-other-user/' + this.user.id)).then((response) => {
                        this.messageList = response.data;
                    });
                }

                // Not sure why $nextTick doesn't work here.
                setTimeout(() => {
                    document.querySelector('.sc-user-input--text').focus();
                }, 500);
            },
            listenForMessages() {
                let usernames = [this.user.username, this.loggedInUser.username];
                usernames.sort();
                let chatKey = 'chat_between_' + usernames[0] + '_' + usernames[1];
                window.Echo.private(chatKey)
                    .listen('MessageSent', (e) => {
                        if (! ('Notification' in window)) {
                            // console.log('Web Notification is not supported');
                            return;
                        }
                        this.messageList.push(
                            { type: 'text', author: `otheruser`, data: { text: e.message.message } },
                        );
                    });
            },
            closeChat () {
                // called when the user clicks on the botton to close the chat
                this.isChatOpen = false
            },
            api(path) {
                path = '/api/v1/' + path;
                if (this.loggedInUser) {
                    path = path + (path.indexOf('?') !== -1 ? '&' : '?') + 'api_token=' + this.loggedInUser.api_token;
                }

                return path;
            },
        },
        computed: {
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser: function() {
                return this.$store.state.user;
            },
        }
    }
</script>