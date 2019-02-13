<template>
    <div class="card saved-search-card hoverable">
        <div class="card--background bg-secondary">
            <h3 class="text-center">
                <router-link v-if="savedSearch.slug" class="naked-link" :to="{name: 'saved-search', params: {slug: savedSearch.slug}}">
                    {{ savedSearch.name }}
                </router-link>
                <router-link v-else class="naked-link" :to="{name: 'saved-search', params: {slug: savedSearch.id}}">
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
                <div class="saved-search--user flex-1" v-for="user in savedSearch.users.slice(0, 6)" v-bind:user="user" :key="user.id">
                    <router-link class="no-link" :to="{ name: 'profile', params: { username: user.username }}">
                        <div class="mini-card m-2 p-3 border border-gray-lighter hover:border-gray-light text-center">
                            <div class="user-avatar inline-block relative">
                                <img class="w-12 h-12 rounded-full border-2 border-secondary-light"
                                     v-bind:src="user.avatar_path">
                                <i v-if="isPresent(user)" class="absolute is-present fas fa-circle"></i>
                            </div>
                            <div>
                                <div class="user-name text-sm mb-1" style="-webkit-box-orient: vertical">
                                    {{ user.name }}
                                </div>
                                <div class="headline text-xs" style="-webkit-box-orient: vertical">
                                    {{ user.headline }}
                                    <br/>
                                    <span v-for="(tag, index) in user.tags">
                                        <span>{{ tag.name }}</span><span v-if="index+1 < user.tags.length">, </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['savedSearch'],
        methods: {
            isPresent(user) {
                let ids = [];
                let presentUsers = this.presentUsers;
                for (let i in presentUsers) {
                    ids.push(presentUsers[i].id);
                }
                return ids.includes(user.id);
            }
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            },
            presentUsers() {
                return this.$store.state.presentUsers;
            },
        },
    }
</script>
