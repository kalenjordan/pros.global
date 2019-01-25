<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                {{ headline }}
            </h1>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2"
                           v-for="user in users.slice(0, 12)"
                           :user="user" :key="user.id"
                />
            </div>
            <div class="centered">
                <router-link class="btn px-5 py-2" :to="{name: 'search-query', params: { query: query }}">
                    See more
                    <i class="fas fa-caret-right ml-2"></i>
                </router-link>
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
                headline: null,
                query: null,
            }
        },
        mounted() {
            axios.get('/api/v1/saved-searches/' + this.$route.params.slug).then((response) => {
                this.users = response.data.users;
                this.headline = response.data.name;
                this.query = response.data.query;
            });
        },
        computed: {
            loggedInUser: function() {
                return this.$store.state.user;
            }
        }
    }
</script>
