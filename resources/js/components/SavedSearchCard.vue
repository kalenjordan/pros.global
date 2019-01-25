<template>
    <div class="card hoverable">
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
</template>

<script>
    export default {
        props: ['savedSearch'],
    }
</script>
