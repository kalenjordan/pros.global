<template>
    <div class="card-group">
        <card-item class="hoverable" v-for="user in users" v-bind:user="user"></card-item>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: []
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users').then(function(response) {
                self.users = response.data;
            });
        }
    }
</script>
