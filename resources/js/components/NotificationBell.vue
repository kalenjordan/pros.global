<template>
    <div class="notification-wrapper">
        <i class="material-icons font-120 cursor-pointer animated" @click="toggleNotifications()">
            notifications
        </i>
        <span v-if="this.unreadNotificationCount" class="alert-bubble absolute bg-highlight rounded-full cursor-pointer"
              @click="toggleNotifications()">
                        {{ this.unreadNotificationCount }}
                    </span>
        <div v-if="showingNotifications" class="card notification-list absolute w-64">
            <div class="card-inner font-120">
                <ul class="list-reset" v-if="notifications.length">
                    <li v-for="notification in notifications" class="px-4 py-2" :class="{'bg-primary-lightest' : notification.read_at === null}">
                        {{ notification.data.text }}
                        <router-link class="paragraph-link" v-if="notification.data.link"
                                     :to="{ name: notification.data.link.name, params: notification.data.link.params}">
                            {{ notification.data.link.cta }}
                        </router-link>
                        <span class="text-gray" v-if="notification.created_at">
                            {{ notification.created_at | moment("subtract", "6 hours") | moment("from") }}
                        </span>
                        <span class="text-gray" v-else>
                            Just now
                        </span>
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
            // this.listenForMessages();
        },
        watch: {
            // Used to work from mounted() but then it stopped and now this works
            loggedInUser(newVal, oldVal) {
                if (newVal && oldVal && (!oldVal.id || newVal.id !== oldVal.id)) {
                    this.initNotificatons();
                }
            }
        },
        methods: {
            initNotificatons() {
                if (this.loggedInUser.api_token) {
                    axios.get(this.api('notifications')).then((response) => {
                        this.$store.commit('updateUnreadNotificationCount', response.data.unread_count);
                        this.$store.commit('updateNotifications', response.data.notifications);
                    });
                }
            },
            // listenForMessages() {
            //     let channel = 'user_notifications_' + this.loggedInUser.id;
            //     window.Echo.private(channel)
            //         .listen('MessageSentNotificationEvent', (e) => {
            //             let count = this.unreadNotificationCount;
            //             count++;
            //             this.$store.commit('updateUnreadNotificationCount', count);
            //
            //             let notifications = this.notifications;
            //             let notification = {data: e.notification};
            //             notification.read_at = null;
            //             notifications.unshift(notification);
            //
            //             this.$store.commit('updateNotifications', notifications);
            //         });
            // },
            toggleNotifications() {
                this.showingNotifications = !this.showingNotifications;
                this.$axios.get(this.$api('notifications/mark-read')).then((response) => {
                    this.$store.commit('updateUnreadNotificationCount', 0);
                });
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