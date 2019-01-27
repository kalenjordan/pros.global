<template>
    <div class="nav flex a items-center">
        <div class="logo-wrapper flex-1 text-left">
            <router-link :to="{name: 'home'}" class="naked-link" href="/">
                <i class="fas fa-bolt font-200"></i>
            </router-link>
        </div>
        <div class="right-nav flex-5 text-right">
            <slot></slot>
            <input ref="search"
                   id="top-nav-search"
                   class="nav--search text w-32 p-2 mr-2"
                   v-bind:class="{'w-48' : isSearching}"
                   placeholder="Search"
                   @focus="isSearching=1"
                   @blur="isSearching=0"
            >
            <div class="inline-block relative" v-if="this.loggedInUser.id">
                <div class="notification-wrapper inline-block mr-4">
                    <i class="fas fa-bell text-gray-dark text-xl cursor-pointer"
                       @click="toggleNotifications()"></i>
                    <span v-if="notifications.length" class="alert-bubble bg-primary rounded-full cursor-pointer"
                          @click="toggleNotifications()">
                        {{ notifications.length }}
                    </span>
                    <div v-if="showingNotifications" class="card notification-list absolute p-4 w-64">
                        <div class="card-inner">
                            <ul class="list-reset">
                                <li v-for="notification in notifications">{{ notification.data.message }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <img class="w-10 rounded-full cursor-pointer"
                     @click="showingMenu = !showingMenu"
                     :src="loggedInUser.avatar_path" style="margin-bottom: -14px;">
                <div v-if="showingMenu" class="card logged-in-menu absolute">
                    <div class="card-inner p-3">
                        <div class="block p-1">
                            <router-link
                                    class="naked-link"
                                    v-if="loggedInUser.id"
                                    :to="{name: 'profile', params: {username: loggedInUser.username}}"
                            >
                                View Profile
                            </router-link>
                        </div>
                        <div class="block p-1">
                            <router-link class="naked-link" :to="{name: 'saved-searches'}">Saved Searches</router-link>
                        </div>
                        <div class="block p-1">
                            <a href="javascript://" class="naked-link" @click="logout">Log out</a>
                        </div>
                        <div class="block p-1" v-if="isAdminViewingProfilePage() && ! isAdminImpersonating()">
                            <a class="naked-link" href="javascript://" @click="impersonate(user)">
                                Impersonate {{ user.first_name }}
                            </a>
                        </div>
                        <div class="block p-1" v-if="isAdminImpersonating()">
                            <a class="naked-link" href="javascript://" @click="leaveImpersonation">
                                Leave impersonation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <a v-else class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Login</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['hideSearch', 'user'],
        data() {
            return {
                isSearching: false,
                showingMenu: false,
                showingNotifications: false,
                notifications: [],
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeyHandler);
            window.Events.$on('user-authenticated', (data) => {
                this.$cookies.set('user', data);
                this.$store.commit('updateUser', JSON.parse(data));

                if (this.$router.currentRoute.name === 'logout') {
                    this.$router.push({name: 'home'});
                }
            });
            if (this.$cookies.get('user')) {
                this.$store.commit('updateUser', this.$cookies.get('user'));
            }

            window.Echo.join('online_presence')
                .here((users) => {
                    console.log("There are " + users.length + " pro(s) online");
                    this.$store.commit('updatePresentUsers', users);
                    console.log(this.presentUsers);
                })
                .joining((user) => {
                    console.log("User joined: " + user.name);
                    let presentUsers = this.presentUsers;
                    presentUsers.push(user);
                    console.log(presentUsers);
                    this.$store.commit('updatePresentUsers', presentUsers);
                })
                .leaving((user) => {
                    console.log('User left: ' + user.name + ' (' + user.id + ')');
                    let presentUsers = this.presentUsers;
                    presentUsers = presentUsers.filter(u => (u.id !== user.id));
                    this.$store.commit('updatePresentUsers', presentUsers);
                });
        },
        methods: {
            focusSearch() {
                if (!this.$refs.search) {
                    return;
                }
                this.isSearching = true;
                this.$refs.search.focus();
            },
            hotkeyHandler(e) {
                if (document.activeElement.id === 'top-nav-search') {
                    if (e.key === 'Enter') {
                        this.search();
                    } else if (e.key === 'Escape') {
                        this.isSearching = false;
                        this.$refs.search.blur();
                    }
                } else if (document.activeElement.tagName === 'BODY') {
                    if (e.key === '/') {
                        this.focusSearch();
                    }
                }
            },
            search() {
                this.$router.push({
                    name: 'search-query',
                    params: {query: this.$refs.search.value},
                });
            },
            isAdminViewingProfilePage() {
                return this.loggedInUser.is_admin && this.user && this.user.id;
            },
            isAdmin() {
                return (this.loggedInUser.is_admin || this.loggedInUser.being_impersonated);
            },
            isAdminImpersonating() {
                return this.loggedInUser.being_impersonated;
            },
            impersonate(user) {
                axios.get('admin/impersonate/' + user.username).then((response) => {
                    if (response.data.username) {
                        this.$cookies.set('user', JSON.stringify(response.data));
                        this.$store.commit('updateUser', response.data);
                        this.$toasted.show("Impersonating " + response.data.name, {duration: 2000});
                    }
                });
            },
            leaveImpersonation() {
                axios.get('admin/leave-impersonation').then((response) => {
                    if (response.data.username) {
                        this.$cookies.set('user', JSON.stringify(response.data));
                        this.$store.commit('updateUser', response.data);
                        this.$toasted.show("Left impersonation", {duration: 2000});
                    }
                });
            },
            logout() {
                axios.get('/auth/logout').then((response) => {
                    this.$cookies.set('user', null);
                    this.$store.commit('updateUser', {});
                    this.$toasted.show("You're logged out! Don't be a stranger now, ya hear? 🤠", {duration: 2000});
                });
            },
            toggleNotifications() {
                this.showingNotifications = !this.showingNotifications;
                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.get('/api/v1/notifications' + auth).then((response) => {
                    this.notifications = response.data;
                });
            }
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            },
            presentUsers() {
                return this.$store.state.presentUsers;
            }
        }
    }
</script>
