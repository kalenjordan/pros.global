<template>
    <div class="footer mx-auto max-w-2xl leading-tight sm:mt-16 pb-16">
        <div class="section footer--inner flex flex-wrap">
            <div class="footer--column flex-2 m-4" style="flex-basis: 15rem;">
                <div class="mb-2">
                    <img class="logo" src="/img/logo.png">
                </div>
                <p class="mb-2">
                    pros.global is a matchmaking platform for founders, eCommerce professionals,
                    and software developers
                    being built by
                    <router-link :to="{ name: 'profile', params: {username: 'kalenjordan'}}">Kalen</router-link>
                    with &hearts; in
                    <router-link :to="{ name: 'saved-search', params: {slug: 'austin'}}">Austin</router-link>
                    .
                </p>
                <p class="mb-2">
                    pros.global is currently open-source-ish. You can take a look at the
                    <a href="https://github.com/kalenjordan/pros.global">readme</a> and get in touch if you're
                    interested in contributing or using it.
                </p>
                <p class="mb-2">
                    If you're interested in signing up to create a profile on the site, just go ahead and
                    <a href="/auth/linkedin" target="_blank">login</a> - once logged in you can edit your profile page.
                </p>
                <p>
                    Copyright 2019. All rights reserved.
                </p>
            </div>

            <div class="footer--column flex-1 m-4" style="flex-basis: 10rem;">
                <h3 class="mb-2">Skills</h3>
                <ul class="list-reset">
                    <template v-for="savedSearch in savedSearches">
                        <li class="pb-2" v-if="savedSearch.icon !== 'fas fa-location-arrow'" :key="savedSearch.id">
                            <router-link class="naked-link" :to="{ name: 'saved-search', params: {slug: savedSearch.slug}}">
                                {{ savedSearch.name }}
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="footer--column flex-1 m-4" style="flex-basis: 10rem;">
                <h3 class="mb-2">Locations</h3>
                <ul class="list-reset">
                    <template v-for="savedSearch in savedSearches">
                        <li class="pb-2" v-if="savedSearch.icon === 'fas fa-location-arrow'" :key="savedSearch.id">
                            <router-link class="naked-link" :to="{ name: 'saved-search', params: {slug: savedSearch.slug}}">
                                {{ savedSearch.name }}
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="footer--column flex-1 m-4" style="flex-basis: 15rem;">
                <h3 class="mb-2">Follow us</h3>
                <p class="mb-1">
                    Sign up to get email updates:
                </p>
                <div class="email-signup mb-4">
                    <input class="text p-2" type="text" style="width: 178px;" placeholder="you@example.com">
                    <a class="btn px-3 py-1"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="text-2xl gray-lighter mb-4">
                    <a class="naked-link mr-2" href="https://twitter.com/kalenjordan"><i class="fab fa-twitter"></i></a>
                    <a class="naked-link mr-2" href="https://github.com/kalenjordan/pros.global"><i class="fab fa-github"></i></a>
                    <a class="naked-link mr-2" href="https://www.linkedin.com/company/35561588/"><i class="fab fa-linkedin"></i></a>
                </div>
                <div v-if="user && this.loggedInUser && this.loggedInUser.is_admin">
                    <a class="naked-link" href="javascript://" @click="mergeUser">Merge user</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                savedSearches: [],
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeys);

            axios.get('/api/v1/saved-searches?featured_min=10&featured_max=99').then((response) => {
                this.savedSearches = response.data;
            });

            this.initCookies();
            this.initServiceWorker();
        },
        methods: {
            hotkeys(e) {
                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'a') {
                        //  none
                    }
                }
            },
            initCookies() {
                this.$cookies.config('30d');
                let user = this.$cookies.get('user');

                if (!user) {
                    axios.get('/auth/me').then((response) => {
                        if (response.data.id) {
                            this.$cookies.set('user', response.data);
                        }
                    });
                }
            },
            initServiceWorker() {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
                        // Registration was successful
                        // console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function (err) {
                        // registration failed :(
                        console.log('ServiceWorker registration failed: ', err);
                    });
                }
            },
            mergeUser() {
                let username = prompt('Enter username');
                let auth = '?api_token=' + this.loggedInUser.api_token;

                axios.get('/api/v1/users/' + this.user.username + '/merge/' + username + auth).then((response) => {
                    alert(response.message);
                });
            },
        },
        computed: {
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser: function() {
                return this.$store.state.user;
            },
        },
    }
</script>
