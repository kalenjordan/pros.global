<template>
    <div class="card">
        <div class="card--background"></div>
        <div class="card--inner">
            <div class="card--avatar">
                <a href="#">
                    <img v-bind:src="user.image_url">
                </a>
            </div>
            <div class="card--cta">
                <a class="btn" href="javascript://" @click="giveProps"><i class="far fa-thumbs-up"></i> Give Props</a>
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
                <a class="tag" href="#"><i class="fa fa-location-arrow"></i> {{ user.city }}</a>
                <a class="tag" href="#" v-for="tag in user.tags" >{{ tag}}</a>
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
            giveProps() {
                alert("Look ma'! I'm bonified! I'm not using jquery any more!");
            }
        }
    }
</script>
