<template>
    <div class="footer mx-auto max-w-2xl leading-tight sm:mt-16 pb-16">
        <div class="section footer--inner flex flex-wrap mx-4">
            <div class="footer--column flex-2 m-2" style="flex-basis: 15rem;">
                <div class="mb-2">
                    <img class="logo" src="/img/logo.png">
                </div>
                <p class="mb-2">
                    pros.global is a matchmaking platform for pros
                    being built by
                    <router-link :to="{ name: 'profile', params: {username: 'kalenjordan'}}">Kalen</router-link>
                    with &hearts; in
                    <router-link :to="{ name: 'saved-search', params: {slug: 'austin'}}">Austin</router-link>.
                </p>
                <p>
                    Copyright 2019. All rights reserved.
                </p>
            </div>

            <div class="footer--column flex-1 p-3" style="flex-basis: 10rem;">
                <h3 class="mb-2">Skills</h3>
                <ul class="list-reset">
                    <li><a class="naked-link" href="https://github.com/kalenjordan/founderland">Open source</a></li>
                    <template v-for="savedSearch in savedSearches">
                        <li v-if="savedSearch.icon !== 'fas fa-location-arrow'" :key="savedSearch.id">
                            <router-link class="naked-link" :to="{ name: 'saved-search', params: {slug: savedSearch.slug}}">
                                {{ savedSearch.name }}
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="footer--column flex-1 p-3" style="flex-basis: 10rem;">
                <h3 class="mb-2">Locations</h3>
                <ul class="list-reset">
                    <template v-for="savedSearch in savedSearches">
                        <li v-if="savedSearch.icon === 'fas fa-location-arrow'" :key="savedSearch.id">
                            <router-link class="naked-link" :to="{ name: 'saved-search', params: {slug: savedSearch.slug}}">
                                {{ savedSearch.name }}
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="footer--column flex-1 p-3" style="flex-basis: 15rem;">
                <h3 class="mb-2">Follow us</h3>
                <p class="mb-1">
                    Sign up to get email updates:
                </p>
                <div class="email-signup mb-4">
                    <input class="text p-1" type="text" style="width: 178px;" placeholder="you@example.com">
                    <a class="btn px-3 py-1"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="text-2xl gray-lighter">
                    <a class="naked-link mr-2" href="https://twitter.com/kalenjordan"><i class="fab fa-twitter"></i></a>
                    <a class="naked-link mr-2" href="https://github.com/kalenjordan/founderland"><i class="fab fa-github"></i></a>
                    <a class="naked-link mr-2" href="https://github.com/kalenjordan/founderland"><i class="fab fa-linkedin"></i></a>
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

                if (! user) {
                    axios.get('auth/me').then((response) => {
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
            }
        },
    }
</script>
