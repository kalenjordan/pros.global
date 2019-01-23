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
                :colors="colors"
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
                ], // the list of all the participant of the conversation. `name` is the user name, `id` is used to establish the author of a message, `imageUrl` is supposed to be the user avatar.
                titleImageUrl: 'https://a.slack-edge.com/66f9/img/avatars-teams/ava_0001-34.png',
                messageList: [
                    { type: 'text', author: `me`, data: { text: `Say yes!` } },
                    { type: 'text', author: `user1`, data: { text: `No.` } }
                ], // the list of the messages to show, can be paginated and adjusted dynamically
                newMessagesCount: 0,
                isChatOpen: false, // to determine whether the chat window should be open or closed
                showTypingIndicator: '', // when set to a value matching the participant.id it shows the typing indicator for the specific user
                colors: {
                    header: {
                        bg: '#4e8cff',
                        text: '#ffffff'
                    },
                    launcher: {
                        bg: '#4e8cff'
                    },
                    messageList: {
                        bg: '#ffffff'
                    },
                    sentMessage: {
                        bg: '#4e8cff',
                        text: '#ffffff'
                    },
                    receivedMessage: {
                        bg: '#eaeaea',
                        text: '#222222'
                    },
                    userInput: {
                        bg: '#f4f7f9',
                        text: '#565867'
                    }
                }, // specifies the color scheme for the component
                alwaysScrollToBottom: false,
                messageStyling: true,
            }
        },
        mounted() {
            console.log(this.user);
        },
        methods: {
            sendMessage (text) {
                if (text.length > 0) {
                    this.newMessagesCount = this.isChatOpen ? this.newMessagesCount : this.newMessagesCount + 1
                    this.onMessageWasSent({ author: 'support', type: 'text', data: { text } })
                }
            },
            onMessageWasSent (message) {
                // called when the user sends a message
                this.messageList = [ ...this.messageList, message ]
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
            },
            closeChat () {
                // called when the user clicks on the botton to close the chat
                this.isChatOpen = false
            },
        }
    }
</script>