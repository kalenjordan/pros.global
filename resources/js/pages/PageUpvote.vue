<template>
    <div class="page page-upvote m-4 sm:m-8">
        <top-nav class="mb-4">
            <div v-if="editing" class="edit-profile-wrapper m-1 inline-block">
                <div class="inline-block">
                    <a class="paragraph-link mr-3" @click="cancel()" v-shortkey="['esc']" @shortkey="cancelEditing()">
                        Cancel
                    </a>
                    <a class="btn px-5 py-2" @click="save()" v-shortkey="['meta', 'enter']" @shortkey="save()">Save</a>
                </div>
            </div>
        </top-nav>
        <section class="hero text-center max-w-lg mb-4 mx-auto">
            <div class="avatar mb-3">
                <router-link :to="{name: 'profile', params: {username: upvote.tagged_username}}">
                    <img v-bind:src="upvote.tagged_user_avatar"
                         class="w-16 sm:w-32 h-16 sm:h-32 rounded-full border-4 border-secondary-light hover:border-secondary"
                    >
                </router-link>
            </div>
            <div class="inline-block" @click="editIfOwner()" v-html="markdown(this.upvote.message)"></div>
        </section>
        <section class="mx-auto max-w-sm text-md">
            <div class="card mb-8">
                <div class="card--inner text-left p-4 leading-normal">
                    <div v-if="editing">
                        <textarea ref="message" class="width-100" rows="30">{{ upvote.message }}</textarea>
                    </div>
                    <div v-else v-html="markdown(this.upvote.message)" @click="editIfOwner()"></div>
                </div>
            </div>
                <div class="text-center">
                    <router-link class="naked-link block" :to="{name: 'profile', params: {username: upvote.author_username}}">
                        <img class="w-8 h-8 rounded-full border-2 border-primary-lighter hover:border-primary"
                             v-bind:src="upvote.author_avatar">
                    </router-link>
                    <router-link class="naked-link block" :to="{name: 'profile', params: {username: upvote.author_username}}">
                        {{ upvote.author_firstname }}
                    </router-link>
                </div>
        </section>
        <footer-component></footer-component>
        <keyboard-shortcuts></keyboard-shortcuts>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                upvote: {},
                editing: false,
            }
        },
        mounted() {
            axios.get('/api/v1/upvotes/' + this.$route.params.id).then((response) => {
                this.upvote = response.data;
            });
        },
        methods: {
            editIfOwner() {
                if (! this.loggedInUser.id) {
                    return;
                }

                if (this.loggedInUser.id !== this.upvote.author_id) {
                    return;
                }

                this.editing = true;
                this.$nextTick(() => {
                    this.$refs.message.focus();
                });
            },
            cancel() {
                this.editing = false;
            },
            save() {
                this.editing = false;
                this.upvote.message = this.$refs.message.value;
                this.$toasted.show('Saved', {duration: 5000, position: "bottom-right"});

                let auth = '?api_token=' + window.api_token;
                axios.post("/api/v1/upvotes/" + this.upvote.id + auth, {
                    'message': this.upvote.message
                }).then((response) => {
                    this.user = response.data;
                });
            },
            markdown: function (content) {
                let converter = new showdown.Converter();
                return converter.makeHtml(content);
            },
            timeAgo(upvote) {
                return moment(upvote.created_at).fromNow();
            }
        },
        computed: {
            loggedInUser() {
                return this.$cookies.get('user');
            }
        }
    }
</script>
