<template>
    <div class="card user-card">
        <div class="card--inner flex items-center p-4 flex-wrap">
            <div class="flex-2 text-center -ml-4 -mr-2">
                <div class="card--avatar">
                    <router-link :to="{ name: 'profile', params: { username: user.username }}">
                        <img v-bind:src="user.avatar_path" class="width-40p">
                    </router-link>
                </div>
                <div class="card--identity">
                    <div class="card--identity--name bold font-90">
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
            </div>
            <div class="flex-3">
                <div class="card--about text-sm leading-tight">
                    {{ user.headline }}
                </div>
            </div>
            <div class="flex-4 card--tags">
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
