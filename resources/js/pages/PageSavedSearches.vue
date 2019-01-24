<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-12 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                Saved searches
            </h1>
        </section>
        <section class="max-w-3xl mb-8 mx-auto">
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <div class="card mb-12 hoverable m-4" v-for="savedSearch in savedSearches">
                    <div class="card--background bg-secondary">
                        <h3 class="text-center">
                            <router-link class="naked-link" :to="{name: 'search', params: {query: savedSearch.query}}">
                                {{ savedSearch.name }}
                            </router-link>
                        </h3>
                    </div>
                    <div class="card--avatar">
                        <div class="card--avatar--inner">
                            <div class="icon-wrapper">
                                <i v-if="savedSearch.icon" :class="savedSearch.icon"></i>
                                <i v-else class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card--inner p-2">
                        <div class="saved-search--users flex flex-wrap">
                            <div class="saved-search--user flex-1" v-for="user in savedSearch.users" v-bind:user="user" :key="user.id">
                                <router-link class="no-link" :to="{ name: 'profile', params: { username: user.username }}">
                                    <div class="mini-card m-2 p-3 border border-gray-lighter hover:border-gray-light text-center">
                                        <div>
                                            <img class="w-12 h-12 rounded-full border-2 border-secondary-light"
                                                 v-bind:src="user.avatar_path">
                                        </div>
                                        <div>
                                            <div class="text-sm mb-1">
                                                {{ user.name }}
                                            </div>
                                            <div class="headline text-xs" style="-webkit-box-orient: vertical">
                                                {{ user.headline }}
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <keyboard-shortcuts></keyboard-shortcuts>
        <footer-component></footer-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                savedSearches: [],
                loggedInUser: {},
            }
        },
        mounted() {
            this.loggedInUser = this.$cookies.get('user') ? this.$cookies.get('user') : {};
            let auth = '&api_token=' + this.loggedInUser.api_token;

            axios.get('/api/v1/saved-searches').then((response) => {
                this.savedSearches = response.data;
            });
       },
    }
</script>
