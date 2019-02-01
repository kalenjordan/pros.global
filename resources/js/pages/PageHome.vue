<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4">
            <h1 id="typewriter" class="mx-4 text-2xl sm:text-4xl">
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
                <router-link v-if="homeSavedSearch.query" class="btn px-5 py-2" :to="{name: 'search-query', params: { query: homeSavedSearch.query }}">
                    See more
                    <i class="fas fa-caret-right ml-2"></i>
                </router-link>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mx-auto">
            <h2 class="text-center mx-auto mb-8">Browse by category</h2>
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <saved-search-card class="mb-12 m-4" v-for="savedSearch in savedSearches" :key="savedSearches.id" :savedSearch="savedSearch"/>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mt-8 mx-auto">
            <h2 class="text-center mx-auto mb-8">About</h2>
            <div class="font-120 mx-auto px-4" style="max-width: 40rem;">
                <p class="mb-4">
                    The goal of this platform is to faciliate various types of matchmaking. Having run a
                    <a href="https://commercehero.io">matchmaking platform</a> in a specific eCommerce developer niche successfully
                    for the last 2 years, I'm taking some of the learnings from that, and trying to create an offering that's a little bit more broad and flexible.
                </p>
                <p class="mb-4">
                    I'm not exactly sure what audiences will be best served by this, but currently the platform is being seeded with people in the communities that I'm most connected to—eCommerce professionals, developers, and bootstrapped software founders.
                </p>
                <p class="mb-4">
                    As the number of people on the platform grows, the value that we can provide in matchmaking for the purposes of hiring employees, contractors, or even finding a co-founder, advisor, or investor should improve.
                </p>
                <p class="mb-4">
                    If you have any questions, I'd love to
                    <a href="https://twitter.com/kalenjordan">hear from you</a>!
                </p>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
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
            axios.get('/api/v1/saved-searches?limit=3&featured_min=100&with_users=1').then((response) => {
                this.savedSearches = response.data;
            });
            axios.get('/api/v1/saved-searches?limit=1&featured_min=1000&with_users=1').then((response) => {
                this.homeSavedSearch = response.data[0];
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
                .typeString('eCommerce people.')
                .pauseFor(1000)
                .deleteChars(17)
                .typeString('pros.')
                .start();
        },
        computed: {
            loggedInUser: function () {
                return this.$store.state.user;
            },
            unreadNotificationCount() {
                return this.$store.state.unreadNotificationCount;
            },
        },
        metaInfo() {
            let notificationCount = this.unreadNotificationCount ? '(' + this.unreadNotificationCount + ') ' : '';
            return {
                title: notificationCount + window.app_name + " - Connect with awesome pros",
            }
        },
    }
</script>
