<template>
    <div class="nav flex pt-8 pl-8 pr-8">
        <div class="logo-wrapper flex-1 text-left">
            <a href="/" v-shortkey="['shift', 'h']" @shortkey="goHome()">
                <i class="fas fa-bolt font-200 mb-4"></i>
            </a>
        </div>
        <div class="right-nav flex-1 text-right">
            <input ref="search"
                   class="text w-32 p-2 mr-2"
                   v-bind:class="{'w-64' : isSearching}"
                   placeholder="Search"
                   v-shortkey="['/']"
                   @shortkey="focusSearch()"
                   @focus="isSearching=1"
                   @blur="isSearching=0"
            >
            <a class="btn px-5 py-2">Sign up</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['linklogo'],
        data() {
            return {
                isSearching: false
            }
        },
        mounted() {
            // nada
        },
        methods: {
            focusSearch() {
                this.isSearching = true;
                this.$refs.search.focus();
            },
            goHome() {
                alert('home');
            }
        }
    }
</script>
