<template>
    <div>
        <div class="nav flex items-center">
            <div class="logo">
                <router-link :to="{name: 'home'}" class="naked-link" href="/">
                    <img class="logo w-3rem" src="/img/icon.png">
                </router-link>
            </div>
            <div class="ml-auto mr-6" v-if="!isSearching" @click="focusSearch()">
                <i class="fas fa-search text-gray-dark font-120 cursor-pointer"></i>
            </div>
            <slot></slot>
            <div class="mr-6" v-if="!isSearching">
                <notification-bell></notification-bell>
            </div>
            <div v-if="!isSearching">
                <div>
                    <img v-if="loggedIn" class="animate avatar w-10 rounded-full cursor-pointer border-2"
                         @click="showingMenu = !showingMenu"
                         :src="loggedInUser.avatar_path">
                    <a v-else class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Login</a>
                </div>
                <div v-if="showingMenu" class="card logged-in-menu absolute">
                    <div class="card-inner p-3 font-120">
                        <div class="block p-2">
                            <router-link class="naked-link" v-if="loggedIn"
                                         :to="{name: 'profile', params: {username: loggedInUser.username}}">
                                View Profile
                            </router-link>
                        </div>
                        <div class="block p-2">
                            <router-link class="naked-link" :to="{name: 'saved-searches'}">Saved Searches</router-link>
                        </div>
                        <div class="block p-2">
                            <a href="javascript://" class="naked-link" @click="logout">Log out</a>
                        </div>
                        <div class="block p-2" v-if="isAdminViewingProfilePage() && ! isAdminImpersonating()">
                            <a class="naked-link" href="javascript://" @click="impersonate(user)">
                                Impersonate {{ user.first_name }}
                            </a>
                        </div>
                        <div class="block p-2" v-if="isAdminImpersonating()">
                            <a class="naked-link" href="javascript://" @click="leaveImpersonation">
                                Leave impersonation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ml-auto" v-if="isSearching" @blur="isSearching=0">
                <ais-index :app-id="process.env.MIX_ALGOLIA_APP_ID"
                           :api-key="process.env.MIX_ALGOLIA_PUBLIC_KEY"
                           :index-name="process.env.MIX_ALGOLIA_INDEX">
                    <ais-search-box autofocus></ais-search-box>
                    <ais-results>
                        <template slot-scope="{ result }">
                            <router-link class="no-link" :to="{path: result.url}">
                                <img v-if="result.type==='user'" class="w-8 rounded-full" :src="result.avatar_path">
                                <i v-if="result.type==='tag'" class="fas fa-tag"></i>
                                <span class="name" style="-webkit-box-orient: vertical;">{{ result.name }}</span>
                            </router-link>
                        </template>
                    </ais-results>
                    <ais-pagination></ais-pagination>
                </ais-index>

                <!--<input ref="search"-->
                <!--id="top-nav-search"-->
                <!--class="nav&#45;&#45;search text w-64 p-2"-->
                <!--placeholder="Search"-->
                <!--@focus="isSearching=1"-->
                <!--@blur="isSearching=0"-->
                <!--&gt;-->
            </div>
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
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeyHandler);
            window.Events.$on('user-authenticated', (data) => {
                this.$cookies.set('user', data);
                this.$store.commit('updateUser', JSON.parse(data));
                this.broadcastPresence();
            });

            if (this.$cookies.get('user')) {
                this.$store.commit('updateUser', this.$cookies.get('user'));
            }

            if (this.loggedIn) {
                this.broadcastPresence();
                axios.get('/auth/me').then((response) => {
                    if (response.data.error_message) {
                        this.logout();
                    }
                });
            }
        },
        methods: {
            broadcastPresence() {
                window.Echo.join('online_presence')
                    .here((users) => {
                        this.$store.commit('updatePresentUsers', users);
                    })
                    .joining((user) => {
                        let presentUsers = this.presentUsers;
                        presentUsers.push(user);
                        this.$store.commit('updatePresentUsers', presentUsers);
                    })
                    .leaving((user) => {
                        let presentUsers = this.presentUsers;
                        presentUsers = presentUsers.filter(u => (u.id !== user.id));
                        this.$store.commit('updatePresentUsers', presentUsers);
                    });
            },
            focusSearch() {
                this.isSearching = true;
                this.$nextTick(() => {
                    if (this.$refs.search) {
                        this.$refs.search.focus();
                    }
                });
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
        },
        computed: {
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser() {
                return this.$store.state.user;
            },
            presentUsers() {
                return this.$store.state.presentUsers;
            },
        },
    }
</script>
