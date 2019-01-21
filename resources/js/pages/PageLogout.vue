<template>
    <div class="page-logged-out">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl">
                See ya soon, pardner 🤠
            </h1>
        </section>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.$cookies.set('user', null);
            window.Events.$emit('user-logged-out');

            axios.get('/auth/logout').then((response) => {
                console.log('logged out');
            });
        },
    }
</script>
