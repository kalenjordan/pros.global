<template>
    <div class="container page-profile">
        <div class="header centered pb-1">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
            <div class="avatar mb-1">
                <img v-bind:src="user.image_url">
            </div>
            <h1>
                {{ user.headline }}
            </h1>
        </div>
        <div class="section centered margin-auto">
            <div class="tag"><i class="fa fa-location-arrow pr-05"></i> {{ user.city }}</div>
            <tag-clickable v-for="tag in user.tags" v-bind:tag="tag"></tag-clickable>
        </div>
        <div class="section margin-auto">
            <div class="card">
                <div class="card--inner text-left">
                    <p>
                        Hi there.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {}
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users/1').then(function(response) {
                self.user = response.data;
            });
        }
    }
</script>
