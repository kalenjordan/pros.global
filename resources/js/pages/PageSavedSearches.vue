<template>
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-12 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                Saved searches
            </h1>
        </section>
        <section class="max-w-3xl mb-8 mx-auto">
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <saved-search-card class="mb-12 m-4" v-for="savedSearch in savedSearches" :key="savedSearches.id" :savedSearch="savedSearch" />
            </div>
        </section>
        <keyboard-shortcuts></keyboard-shortcuts>
        <footer-component></footer-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                savedSearches: [],
            }
        },
        mounted() {
            axios.get('/api/v1/saved-searches?with_users=1').then((response) => {
                this.savedSearches = response.data;
            });
       },
    }
</script>
