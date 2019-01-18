<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                Tag, endorse, and discover cool founders
            </h1>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2" v-for="user in users" v-bind:user="user" :key="user.id"></user-card>
                <tag-endorsement></tag-endorsement>
            </div>
            <div class="centered">
                <p class="mb-0">
                    <router-link :to="{name: 'search-query', params: { query: 'tag:founder' }}">See more founders</router-link>
                </p>
            </div>
        </section>
        <!--<hr/>-->
        <!--<div class="section centered pb-2">-->
            <!--<h2 class="mb-4">-->
                <!--Discover by tag-->
            <!--</h2>-->
            <!--<div class="tag-cards mx-auto max-w-lg mb-8 flex">-->
                <!--<router-link class="no-link flex-1" v-for="tag in tags" :to="{ name: 'tag', params: { slug: tag.slug }}">-->
                    <!--<div class="card hoverable tag-card">-->
                        <!--<div class="card&#45;&#45;inner">-->
                            <!--<div class="bold">{{ tag.name }}</div>-->
                            <!--<div class="font-70">{{ tag.count }} people</div>-->
                            <!--<div class="mt-1">-->
                                <!--{{ tag.description }}-->
                            <!--</div>-->
                            <!--<div class="mt-1">-->
                                <!--<img v-for="user in tag.users" v-bind:src="user.avatar_path">-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
            <!--</div>-->
            <!--<div class="text-center">-->
                <!--<p class="mb-0">-->
                    <!--<router-link :to="{name: 'tags'}">See more tags</router-link>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <keyboard-shortcuts></keyboard-shortcuts>
        <footer-component></footer-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                tags: [],
                isSearching: false
            }
        },
        mounted() {
            let auth = '&api_token=' + window.api_token;
            axios.get('/api/v1/users?q=tag:founder' + auth).then((response) => {
                this.users = response.data;
            });
            axios.get('/api/v1/tags?limit=3').then((response) => {
                this.tags = response.data;
            });
        },
    }
</script>
