<template>
    <div class="card user-card">
        <div class="card--inner flex">
            <div class="flex-1 centered">
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
            <div class="flex-2 ml-1">
                <div class="card--about font-small">
                    {{ user.headline }}
                </div>
            </div>
            <div class="flex-3 ml-1">
                <div class="card--tags font-70">
                    <div class="tag"><i class="fa fa-location-arrow"></i> {{ user.city }}</div>
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
