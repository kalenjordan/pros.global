<template>
    <div class="page page-upvote m-4 sm:m-8">
        <top-nav class="mb-4"></top-nav>
        <section class="hero text-center max-w-lg mb-4 mx-auto">
            <div class="avatar mb-1">
                <router-link :to="{name: 'profile', params: {username: upvote.tagged_username}}">
                    <img v-bind:src="upvote.tagged_user_avatar"
                         class="w-16 sm:w-32 h-16 sm:h-32 rounded-full border-4 border-secondary-light hover:border-secondary"
                    >
                </router-link>
            </div>
            <div class="inline-block" v-html="markdown(this.upvote.message)"></div>
        </section>
        <section class="mx-auto max-w-sm text-md">
            <div class="card mb-8">
                <div class="card--inner text-left p-4 leading-normal">
                    <div v-html="markdown(this.upvote.message)"></div>
                </div>
            </div>
                <div class="text-center">
                    <router-link class="naked-link block" :to="{name: 'name', params: {}}">
                        <img class="w-8 h-8 rounded-full border-2 border-primary-lighter hover:border-primary"
                             v-bind:src="upvote.author_avatar">
                    </router-link>
                    <router-link class="naked-link block" :to="{name: 'name', params: {}}">
                        {{ upvote.author_firstname }}
                    </router-link>
                </div>
        </section>
        <footer-component></footer-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                upvote: {},
            }
        },
        mounted() {
            axios.get('/api/v1/upvotes/' + this.$route.params.id).then((response) => {
                this.upvote = response.data;
            });
        },
        methods: {
            markdown: function (content) {
                let converter = new showdown.Converter();
                return converter.makeHtml(content);
            },
            timeAgo(upvote) {
                return moment(upvote.created_at).fromNow();
            }
        },
    }
</script>
