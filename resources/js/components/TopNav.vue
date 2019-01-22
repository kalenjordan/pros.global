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
                        <div>
                            <router-link
                                    class="naked-link"
                                    v-if="loggedInUser.id"
                                    :to="{name: 'profile', params: {username: loggedInUser.username}}"
                            >
                                View Profile
                            </router-link>
                        </div>
                        <div>
                            <router-link class="naked-link" :to="{name: 'saved-searches'}">Saved Searches</router-link>
                        </div>
                        <div>
                            <router-link class="naked-link" :to="{name: 'logout'}">Log out</router-link>
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
        props: ['hideSearch'],
        data() {
            return {
                isSearching: false,
                loggedInUser: {},
                showingMenu: false,
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeyHandler);
            window.Events.$on('user-authenticated', (data) => {
                console.log('TopNav.vue $on user-authenticated');
                console.log(data);
                this.$cookies.set('user', data);
                this.loggedInUser = JSON.parse(data);
            });
            window.Events.$on('user-logged-out', (data) => {
                // this.$cookies.set('user', null) called from PageLogout.vue
                this.loggedInUser = {};
            });
            if (this.$cookies.get('user')) {
                this.loggedInUser = this.$cookies.get('user');
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
        }
    }
</script>
