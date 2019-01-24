<template>
    <div class="page-logged-out">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                You're logged out!
                <br/>Don't be a stranger now, ya hear? 🤠
            </h1>
        </section>
        <footer-component></footer-component>
    </div>
</template>
<script>
    export default {
        mounted() {
            axios.get('/auth/logout').then((response) => {
                this.$cookies.set('user', null);
                this.$store.commit('updateUser', {});
            });
        },
    }
</script>
