<template>
    <div class="card">
        <div class="card--background"></div>
        <div class="card--inner">
            <div class="card--avatar">
                <router-link to="/user/1">
                    <img v-bind:src="user.image_url">
                </router-link>
            </div>
            <div class="card--cta">
                <router-link class="btn" to="/user/1">View Profile</router-link>
            </div>
            <div class="card--identity mb-1">
                <div class="card--identity--name bold">
                    <a href="#" class="naked-link">{{ user.name }}</a>
                </div>
                <div class="card--identity--handle font-small">
                    <a href="#" class="naked-link">{{ user.handle }}</a>
                </div>
            </div>
            <div class="card--about font-small mb-1">
                {{ user.description }}
            </div>
            <div class="card--tags font-70">
                <span class="tag"><i class="fa fa-location-arrow"></i> {{ user.city }}</span>
                <span class="tag" v-for="tag in user.tags" @click="tagClick">
                    <i class="far fa-thumbs-up"></i>
                    <span class="tag-name">{{ tag.tag }}</span>
                    <span class="tag-count">{{ tag.count }}</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        mounted() {
            // nada
        },
        methods: {
            tagClick() {
                alert('clicked');
            }
        }
    }
</script>
