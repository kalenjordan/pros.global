<template>
    <div class="notification-wrapper inline-block mr-4">
        <i class="fas fa-bell text-gray-dark text-xl cursor-pointer ml-2"
           @click="toggleNotifications()"></i>
        <span v-if="this.unreadNotificationCount" class="alert-bubble bg-primary rounded-full cursor-pointer"
              @click="toggleNotifications()">
                        {{ this.unreadNotificationCount }}
                    </span>
        <div v-if="showingNotifications" class="card notification-list absolute p-2 w-64">
            <div class="card-inner">
                <ul class="list-reset" v-if="notifications.length">
                    <li v-for="notification in notifications" class="p-2">
                        {{ notification.data.text }}
                        <router-link class="paragraph-link" v-if="notification.data.link"
                                     :to="{ name: notification.data.link.name, params: notification.data.link.params}">
                            {{ notification.data.link.cta }}
                        </router-link>
                        <span class="text-gray">{{ notification.created_at | moment("from") }}</span>
                    </li>
                </ul>
                <ul class="list-reset" v-else>
                    <li class="p-2">No notifications yet</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['hideSearch', 'user'],
        data() {
            return {
                showingNotifications: false,
            }
        },
        mounted() {
            // watch doesn't fire when I moved this from topnav.vue into here, but it works in mounted.
            this.initNotificatons();
            this.listenForMessages();
        },
        methods: {
            initNotificatons() {
                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.get('/api/v1/notifications' + auth).then((response) => {
                    this.$store.commit('updateUnreadNotificationCount', response.data.unread_count);
                    this.$store.commit('updateNotifications', response.data.notifications);
                });
            },
            listenForMessages() {
                let chatKey = 'user_notifications_' + this.loggedInUser.id;
                console.log(chatKey);
                window.Echo.private(chatKey)
                    .listen('MessageSentNotificationEvent', (e) => {
                        let count = this.unreadNotificationCount;
                        count++;
                        this.$store.commit('updateUnreadNotificationCount', count);

                        let notifications = this.notifications;
                        notifications.push({data: e.notification});
                        this.$store.commit('updateNotifications', notifications);
                    });
            },
            toggleNotifications() {
                this.showingNotifications = !this.showingNotifications;
                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.get('/api/v1/notifications/mark-read' + auth).then((response) => {
                    this.$store.commit('updateUnreadNotificationCount', 0);
                });
            },
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            },
            unreadNotificationCount() {
                return this.$store.state.unreadNotificationCount;
            },
            notifications() {
                return this.$store.state.notifications;
            },
        },
    }
</script>