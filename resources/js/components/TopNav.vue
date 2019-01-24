<template>
    <div class="nav flex a items-center">
        <div class="logo-wrapper flex-1 text-left">
            <router-link :to="{name: 'home'}" class="naked-link" href="/">
                <i class="fas fa-bolt font-200"></i>
            </router-link>
        </div>
        <div class="right-nav flex-5 text-right">
            <input ref="search"
                   id="top-nav-search"
                   class="nav--search text w-32 p-2 mr-2"
                   v-bind:class="{'w-48' : isSearching}"
                   placeholder="Search"
                   @focus="isSearching=1"
                   @blur="isSearching=0"
            >
            <slot></slot>
            <div class="inline-block relative" v-if="this.loggedInUser.id">
                <img class="w-10 rounded-full cursor-pointer" @click="showingMenu = !showingMenu" :src="loggedInUser.avatar_path" style="margin-bottom: -14px;">
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
                            <router-link class="naked-link" :to="{name: 'logout'}">Log out</router-link>
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
        },
        methods: {
            focusSearch() {
                if (! this.$refs.search) {
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
                    params: { query: this.$refs.search.value },
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
                        this.$toasted.show("Impersonating " + response.data.name);
                    }
                });
            },
            leaveImpersonation() {
                axios.get('admin/leave-impersonation').then((response) => {
                    if (response.data.username) {
                        this.$cookies.set('user', JSON.stringify(response.data));
                        this.$store.commit('updateUser', response.data);
                        this.$toasted.show("Left impersonation");
                    }
                });
            },
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            }
        }
    }
</script>
