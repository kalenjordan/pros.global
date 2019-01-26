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
                :messageStyling="messageStyling"/>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                participants: [
                    {
                        id: 'user1',
                        name: 'Placeholder',
                        imageUrl: 'https://avatars3.githubusercontent.com/u/1915989?s=230&v=4'
                    },
                ],
                titleImageUrl: 'https://a.slack-edge.com/66f9/img/avatars-teams/ava_0001-34.png',
                messageList: [],
                newMessagesCount: 0,
                isChatOpen: false, // to determine whether the chat window should be open or closed
                showTypingIndicator: '', // when set to a value matching the participant.id it shows the typing indicator for the specific user
                alwaysScrollToBottom: false,
                messageStyling: false,
            }
        },
        mounted() {
            window.Events.$on('clicked-chat-notification', () => {
                this.openChat();
            });
        },

        methods: {
            sendMessage (text) {
                if (text.length > 0) {
                    this.newMessagesCount = this.isChatOpen ? this.newMessagesCount : this.newMessagesCount + 1
                    this.onMessageWasSent({ author: 'support', type: 'text', data: { text } })
                }
            },
            onMessageWasSent (message) {
                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.post('/api/v1/messages' + auth, {
                    message: message.data.text,
                    to_user_id: this.user.id,
                }).then((response) => {
                    // called when the user sends a message
                    this.messageList = [ ...this.messageList, message ]
                });
            },
            openChat () {
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
            },
            listenForMessages() {
                let usernames = [this.user.username, this.loggedInUser.username];
                usernames.sort();
                let chatKey = 'chat_between_' + usernames[0] + '_' + usernames[1];
                console.log(chatKey);
                window.Echo.private(chatKey)
                    .listen('MessageSent', (e) => {
                        if (! ('Notification' in window)) {
                            console.log('Web Notification is not supported');
                            return;
                        }
                        this.messageList.push(
                            { type: 'text', author: `user1`, data: { text: e.message.message } },
                        );
                    });
            },
            closeChat () {
                // called when the user clicks on the botton to close the chat
                this.isChatOpen = false
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