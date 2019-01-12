<template>
    <div class="container">
        <router-link to="/">Home</router-link>
        > {{ user.name }}
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
