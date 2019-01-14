<template>
    <div class="container page page-search">
        <div class="header centered pb-1">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
            <div class="flex">
                <input placeholder="e.g. tag:founder" class="text font-100 flex-5 no-border mr-1 p-1">
                <a class="btn flex-1 p-1" style="padding: 12px;" @click="search">
                    <span v-if="search_processing">Searching...</span>
                    <span v-else>Search</span>
                </a>
            </div>
        </div>
        <div class="section" v-bind:class="{opacity50 : search_processing}">
            <search-result-card class="hoverable" v-for="user in users" v-bind:user="user"></search-result-card>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                search_processing: false
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users').then(function(response) {
                self.users = response.data;
            });
        },
        methods: {
            search() {
                let self = this;
                this.search_processing = true;

                setTimeout(function() {
                    self.search_processing = false;
                    self.users.pop();
                }, 1000);
            }
        }
    }
</script>
