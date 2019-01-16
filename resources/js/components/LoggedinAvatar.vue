<template>
    <img class="w-8 rounded-full"
         v-if="loggedInUser"
         :src="loggedInUser.avatar_path"
         style="margin-bottom: -11px;">
</template>

<script>
    export default {
        mounted() {
            // nada
        },
        computed: {
            loggedInUser() {
                return window.user;
            }
        },
    }
</script>
