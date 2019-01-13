<template>
    <div class="card">
        <div class="card--background"></div>
        <div class="card--inner">
            <div class="card--avatar">
                <router-link :to="{ name: 'profile', params: { username: user.handle }}">
                    <img v-bind:src="user.image_url">
                </router-link>
            </div>
            <div class="card--cta">
                <router-link class="btn" :to="{ name: 'profile', params: { username: user.handle }}">
                    View Profile
                </router-link>
            </div>
            <div class="card--identity mb-1">
                <div class="card--identity--name bold">
                    <router-link :to="{ name: 'profile', params: { username: user.handle }}" class="naked-link">
                        {{ user.name }}
                    </router-link>
                </div>
                <div class="card--identity--handle font-small">
                    <router-link :to="{ name: 'profile', params: { username: user.handle }}" class="naked-link">
                        @{{ user.handle }}
                    </router-link>
                </div>
            </div>
            <div class="card--about font-small mb-1">
                {{ user.headline }}
            </div>
            <div class="card--tags font-70">
                <div class="tag"><i class="fa fa-location-arrow"></i> {{ user.city }}</div>
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
