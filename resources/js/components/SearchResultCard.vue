<template>
    <div class="card user-card">
        <div class="card--inner flex items-center p-4 flex-wrap">
            <div class="card--avatar flex-2 text-center -ml-1">
                <router-link :to="{ name: 'profile', params: { username: user.username }}">
                    <img v-bind:src="user.avatar_path" class="width-40p">
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
            <div class="flex-4 card--tags" v-if="user.tags.length" style="-webkit-box-orient: vertical;">
                <div class="font-70">
                    <tag-clickable v-for="tag in user.tags" v-bind:tag="tag" :key="tag.id"></tag-clickable>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
    }
</script>
