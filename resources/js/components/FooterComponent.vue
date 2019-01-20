<template>
    <div class="footer mx-auto max-w-lg text-xs leading-tight sm:mt-16 pb-16">
        <div class="section footer--inner flex flex-wrap">
            <div class="footer--column flex-3 p-3">
                <h3 class="mb-2">About</h3>
                <p class="mb-2">
                    Founder Land is a matchmaking platform for founders
                    being built by
                    <router-link :to="{ name: 'profile', params: {username: 'kalenjordan'}}">Kalen</router-link>
                    with &hearts; in
                    <router-link :to="{ name: 'tag', params: {slug: 'austin'}}">Austin</router-link>.
                </p>
                <p>
                    Copyright 2019. All rights reserved.
                </p>
            </div>

            <div class="footer--column flex-1 p-3">
                <h3 class="mb-2">Resources</h3>
                <ul>
                    <li><a class="naked-link" href="https://github.com/kalenjordan/founderland">Open source</a></li>
                    <li><a class="naked-link" href="/tags">Tags</a></li>
                    <li><a class="naked-link" href="/cities">Cities</a></li>
                </ul>
                <div v-if="isAdmin">
                    <h3 class="mt-4">Admin</h3>
                    <ul>
                        <li v-if="adminViewingProfilePage() && user">
                            <a class="naked-link" v-bind:href="'/admin/impersonate/' + user.username">
                                Impersonate {{ user.username }}
                            </a>
                        </li>
                        <li v-if="adminIsImpersonating()">
                            <a class="naked-link" href="/admin/leave-impersonation">
                                Leave Impersonation
                            </a>
                        </li>
                        <li>
                            <a class="naked-link" href="javascript://" @click="$modal.show('add-twitter-user')">Add user from twitter</a>
                        </li>
                    </ul>
                    <modal name="add-twitter-user" @opened="$refs.twitterUsername.focus()">
                        <input name="username" ref="twitterUsername" v-shortkey="['enter']" @shortkey="addTwitterUser">
                        <a class="btn px-3 py-5" @click="addTwitterUser">Add</a>
                    </modal>
                </div>
            </div>

            <div class="footer--column flex-2 p-3">
                <h3 class="mb-2">Follow us</h3>
                <p class="mb-1">
                    Sign up to get email updates:
                </p>
                <div class="email-signup mb-4">
                    <input class="text p-1" type="text" style="width: 173px;" placeholder="you@example.com">
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
        mounted() {
            window.addEventListener('keyup', this.hotkeys);
        },
        methods: {
            isAdmin() {
                return (window.user.is_admin || window.user.being_impersonated);
            },
            adminViewingProfilePage() {
                return window.user.is_admin && user.id;
            },
            adminIsImpersonating() {
                return window.user.being_impersonated;
            },
            addTwitterUser() {
                let auth = '?api_token=' + window.api_token;
                axios.get('api/v1/twitter/add-user/' + this.$refs.twitterUsername.value + auth).then((response) => {
                    if (response.data.username) {
                        this.$router.push({
                            name: 'profile',
                            params: { username: response.data.username },
                        });
                    } else if (response.data.message) {
                        alert(response.data.message);
                    }
                });
            },
            hotkeys(e) {
                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'a') {
                        this.$modal.show('add-twitter-user');
                    }
                }
            },
        }
    }
</script>
