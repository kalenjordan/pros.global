<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mb-4 mx-4">
            <h1 id="typewriter" class="text-2xl sm:text-4xl">
                {{ homeSavedSearch.name }}
            </h1>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2"
                           v-for="user in homeSavedSearch.users.slice(0, 6)"
                           :user="user" :key="user.id"
                />
            </div>
            <div class="centered">
                <router-link class="btn px-5 py-2" :to="{name: 'search-query', params: { query: homeSavedSearch.query }}">
                    See more
                    <i class="fas fa-caret-right ml-2"></i>
                </router-link>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mx-auto">
            <h2 class="text-center mx-auto mb-8">Browse by category</h2>
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <saved-search-card class="mb-12 m-4" v-for="savedSearch in savedSearches" :key="savedSearches.id" :savedSearch="savedSearch" />
            </div>
        </section>
        <keyboard-shortcuts></keyboard-shortcuts>
        <footer-component></footer-component>
    </div>
</template>

<script>
    import Typewriter from 'typewriter-effect/dist/core';

    export default {
        data() {
            return {
                users: [],
                savedSearches: [],
                homeSavedSearch: {users: []},
            }
        },
        mounted() {
            axios.get('/api/v1/saved-searches?limit=4&featured=1').then((response) => {
                this.savedSearches = response.data;
            });
            axios.get('/api/v1/saved-searches/homepage').then((response) => {
                this.homeSavedSearch = response.data;
            });
            let typewriter = new Typewriter('#typewriter', {
                loop: false
            });

            typewriter.typeString('Connect with awesome founders.')
                .pauseFor(1000)
                .deleteChars(9)
                .typeString('technologists.')
                .pauseFor(1000)
                .deleteChars(14)
                .typeString('eCommerce professionals.')
                .pauseFor(1000)
                .deleteChars(24)
                .typeString('developers.')
                .pauseFor(1000)
                .deleteChars(11)
                .typeString('pros.')
                .start();
        },
        computed: {
            loggedInUser: function() {
                return this.$store.state.user;
            }
        }
    }
</script>
