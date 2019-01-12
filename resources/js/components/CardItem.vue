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
                    <router-link to="/user/1" class="naked-link">{{ user.name }}</router-link>
                </div>
                <div class="card--identity--handle font-small">
                    <router-link to="/user/1" class="naked-link">{{ user.handle }}</router-link>
                </div>
            </div>
            <div class="card--about font-small mb-1">
                {{ user.description }}
            </div>
            <div class="card--tags font-70">
                <span class="tag"><i class="fa fa-location-arrow"></i> {{ user.city }}</span>
                <span class="tag fast"
                      v-for="tag in user.tags"
                      @click="tagClick(tag)"
                      v-bind:class="{tada : tag.is_upvoted, animated : hasBeenClicked}">
                    <i class="far fa-thumbs-up" v-bind:class="{upvoted : tag.is_upvoted}"></i>
                    <span class="tag-name">{{ tag.tag }}</span>
                    <span class="tag-count">{{ tag.count }}</span>
                </span>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    @import './../../../node_modules/animate.css/animate.min.css';
</style>
<script>
    export default {
        props: ['user'],
        mounted() {
            // nada
        },
        data() {
            return {
                hasBeenClicked: false
            }
        },
        methods: {
            tagClick(tag) {
                this.hasBeenClicked = true;
                if (tag.is_upvoted) {
                    tag.count -= 1;
                } else {
                    tag.count += 1;
                }
                tag.is_upvoted = ! tag.is_upvoted;
            }
        }
    }
</script>
