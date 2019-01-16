<template>
    <div class="nav flex pt-8 pl-8 pr-8 a items-center">
        <div class="logo-wrapper flex-1 text-left">
            <a class="naked-link" href="/" v-shortkey="['shift', 'h']" @shortkey="goHome()">
                <i class="fas fa-bolt font-200 mb-4"></i>
            </a>
        </div>
        <div class="right-nav flex-1 text-right"
             v-shortkey="['/']"
             @shortkey="focusSearch()"
        >
            <input ref="search"
                   class="text w-32 p-2 mr-2"
                   v-bind:class="{'w-64' : isSearching}"
                   placeholder="Search"
                   @focus="isSearching=1"
                   @blur="isSearching=0"
                   v-shortkey="['enter']"
                   @shortkey="search()"
            >
            <slot></slot>
            <router-link
                    class="ml-3"
                    v-if="loggedInUser.id"
                    :to="{name: 'profile', params: {username: loggedInUser.username}}"
            >
                <img class="w-8 rounded-full" :src="loggedInUser.avatar_path" style="margin-bottom: -11px;">
            </router-link>
            <a v-else class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Login</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['linklogo'],
        data() {
            return {
                isSearching: false,
                loggedInUser: {},
            }
        },
        mounted() {
            window.Events.$on('user-authenticated', (data) => {
                this.loggedInUser = data;
            });
            if (window.user) {
                this.loggedInUser = window.user;
            }
        },
        methods: {
            focusSearch() {
                this.isSearching = true;
                this.$refs.search.focus();
            },
            search() {
                this.$router.push({
                    name: 'search-query',
                    params: { query: this.$refs.search.value },
                });
            },
            goHome() {
                alert('home');
            },
        }
    }
</script>
