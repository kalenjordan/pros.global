<template>
    <div class="card user-card">
        <div class="card--inner flex items-center p-4 flex-wrap">
            <div class="card--avatar flex-2 text-center -ml-1">
                <router-link class="relative" :to="{ name: 'profile', params: { username: user.username }}">
                    <img v-bind:src="user.avatar_path" class="width-40p">
                    <i v-if="this.isPresent(user)" class="absolute is-present fas fa-circle"></i>
                </router-link>
            </div>
            <div class="flex-3 card--about text-sm sm:ml-2 leading-tight" style="-webkit-box-orient: vertical;" >
                <div class="card--identity--name bold">
                    <router-link :to="{ name: 'profile', params: { username: user.username }}" class="naked-link">
                        {{ user.name }}
                    </router-link>
                </div>
                <div>
                    {{ user.headline }}
                </div>
            </div>
            <div v-if="user.tags.length" class="flex-4  card--tags text-xs" style="-webkit-box-orient: vertical">
                <router-link :to="{ path: '/search/tag:' + tag.slug }" v-for="tag in user.tags" :key="tag.id">
                    <div class="tag animated fast">
                        <span class="tag-name">
                            <template v-if="tag.icon"><i class="tag-icon" :class="tag.icon"></i></template>
                            {{ tag.name }}
                        </span>
                        <span v-if="tag.upvote_count" class="separator">&nbsp;</span>
                        <span class="count-and-upvote">
                            <span v-if="tag.upvote_count" class="tag-count">{{ tag.upvote_count }}</span>
                        </span>
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
