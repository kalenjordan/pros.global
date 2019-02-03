<template>
    <div class="card user-card">
        <div class="card--background bg-primary"></div>
        <div class="card--inner p-4">
            <div class="card--avatar">
                <router-link class="relative" :to="{ name: 'profile', params: { username: user.username }}">
                    <img v-bind:src="user.avatar_path">
                    <i v-if="this.isPresent(user)" class="absolute is-present fas fa-circle"></i>
                </router-link>
            </div>
            <div class="card--cta">
                <router-link class="btn px-5 py-2" :to="{ name: 'profile', params: { username: user.username }}">
                    View
                </router-link>
            </div>
            <div class="card--identity mb-2">
                <div class="card--identity--name bold">
                    <router-link :to="{ name: 'profile', params: { username: user.username }}" class="naked-link">
                        {{ user.name }}
                    </router-link>
                </div>
                <div class="card--identity--handle font-small">
                    <router-link :to="{ name: 'profile', params: { username: user.username }}" class="naked-link">
                        @{{ user.username }}
                    </router-link>
                </div>
            </div>
            <div class="card--about text-xs mb-2 leading-tight" style="-webkit-box-orient: vertical">
                {{ user.headline }}
            </div>
            <div class="card--tags text-xs" style="-webkit-box-orient: vertical">
                <router-link :to="{ path: '/search/tag:' + tag.slug }" v-for="tag in user.tags" :key="tag.id">
                    <div class="tag fast"
                         v-bind:class="{isUpvotedByMe : tag.is_upvoted_by_me}">
                        <template v-if="tag.icon"><i :class="tag.icon"></i></template>
                        <span class="tag-name">{{ tag.name }}</span>
                        <span v-if="tag.upvote_count" class="tag-count">{{ tag.upvote_count }}</span>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
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
