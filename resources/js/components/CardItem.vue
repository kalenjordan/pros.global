<template>
    <div class="card user-card max-w-xs">
        <div class="card--background"></div>
        <div class="card--inner">
            <div class="card--avatar">
                <router-link :to="{ name: 'profile', params: { username: user.username }}">
                    <img v-bind:src="user.avatar_path">
                </router-link>
            </div>
            <div class="card--cta">
                <router-link class="btn px-5 py-2" :to="{ name: 'profile', params: { username: user.username }}">
                    View Profile
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
            <div class="card--about text-xs mb-2 leading-tight">
                {{ user.headline }}
            </div>
            <div class="card--tags text-xs">
                <!--
                <div class="tag"><i class="fa fa-location-arrow"></i> {{ user.city }}</div>
                -->
                <tag-clickable v-for="tag in user.tags" v-bind:tag="tag"></tag-clickable>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
    }
</script>
