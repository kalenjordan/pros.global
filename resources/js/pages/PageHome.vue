<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                Where pros come to connect with, tag, and upvote other pros
            </h1>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2" v-for="user in users" v-bind:user="user" :key="user.id"></user-card>
                <tag-endorsement></tag-endorsement>
            </div>
            <div class="centered">
                <router-link class="btn px-5 py-2" :to="{name: 'search-query', params: { query: 'tag:founder' }}">See more founders</router-link>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mx-auto">
            <h2 class="text-center mx-auto mb-8">Browse by category</h2>
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <div class="card mb-12 hoverable m-4" v-for="savedSearch in savedSearches">
                    <div class="card--background bg-secondary">
                        <h3 class="text-center">{{ savedSearch.name }}</h3>
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
                users: [],
                savedSearches: [],
                isSearching: false
            }
        },
        mounted() {
            let auth = '&api_token=' + this.loggedInUser.api_token;
            axios.get('/api/v1/users?q=tag:founder&limit=6' + auth).then((response) => {
                this.users = response.data;
            });
            axios.get('/api/v1/saved-searches?limit=4&featured=1').then((response) => {
                this.savedSearches = response.data;
            });
        },
        computed: {
            loggedInUser: function() {
                return this.$cookies.get('user');
            }
        }
    }
</script>
