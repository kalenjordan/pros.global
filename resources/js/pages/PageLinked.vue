<template>
    <div>
        <top-nav class="m-4 sm:m-8"></top-nav>
        in here
    </div>
</template>

<script>
    export default {
        mounted() {
            window.Events.$emit('user-authenticated', userData);
            if (window.parent) {
                window.parent.Events.$emit('user-authenticated', userData);
            }
            window.close();
        },
    }
</script>