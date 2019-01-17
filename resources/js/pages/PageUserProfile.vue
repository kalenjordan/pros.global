<template>
    <div class="page page-profile m-4">
        <top-nav class="mb-4">
            <div v-if="editing" class="edit-profile-wrapper m-1 inline-block">
                <div class="inline-block">
                    <a class="paragraph-link mr-3" @click="cancelEditing()" v-shortkey="['esc']" @shortkey="cancelEditing()">
                        Cancel
                    </a>
                    <a class="btn px-5 py-2" @click="saveProfile()" v-shortkey="['meta', 'enter']" @shortkey="saveProfile()">Save</a>
                </div>
            </div>
        </top-nav>
        <section class="header max-w-lg mb-4 mx-auto text-center">
            <div class="avatar mb-1">
                <img v-bind:src="user.avatar_path" class="w-16 sm:w-32">
            </div>
            <h1 class="text-xl sm:text-4xl" @click="editIfOwner()">
                <span v-if="editing" class="editable-headline">
                    <textarea ref="headline" class="text-3xl text-center no-border w-full" >{{ user.headline }}</textarea>
                </span>
                <span v-else>{{ user.headline }}</span>
            </h1>
        </section>
        <div class="mx-auto max-w-md text-center mb-4">
            <user-profile-tags :user="user" :editing="editing"></user-profile-tags>
        </div>
        <div class="section mx-auto max-w-md" v-if="user.about || editing">
            <div class="card">
                <div class="card--inner text-left p-4">
                    <div class="editable-about" v-if="editing">
                        <textarea ref="about" class="font-90 width-100">{{ user.about }}</textarea>
                    </div>
                    <div v-else v-html="compiledMarkdown" @click="editIfOwner()">{{ user.about }}</div>
                </div>
            </div>
        </div>
        <hr v-if="hasUpvotes" class="m-6 sm:my-16 sm:w-md sm:mx-auto" />
        <div v-if="hasUpvotes" class="section endorsements mx-auto max-w-sm">
            <div class="card hoverable endorsement-card mb-4" v-for="upvote in user.upvotes" :key="upvote.id">
                <div class="card--inner p-4">
                    <div class="avatar centered mr-4 -ml-2">
                        <router-link :to="{name: 'profile', params: {username: upvote.author_username }}">
                            <img v-bind:src="upvote.author_avatar">
                        </router-link>
                        {{ upvote.author_firstname }}
                    </div>
                    <div class="endorsement-message">
                        <div>
                            <p class="mb-05">
                                {{ upvote.message }}
                            </p>
                            <div class="inline-tag">{{ upvote.tag_name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <keyboard-shortcuts></keyboard-shortcuts>
    </div>
</template>

<script>
    import KeyboardShortcuts from "../components/KeyboardShortcuts";

    export default {
        components: {KeyboardShortcuts},
        data() {
            return {
                user: {},
                editing: false,
            }
        },
        mounted() {
            let auth = '?api_token=' + window.api_token;
            axios.get('/api/v1/users/' + this.$route.params.username + auth).then((response) => {
                this.user = response.data;
            });
        },
        methods: {
            editIfOwner() {
                if (this.loggedInUserViewingOwnPage()) {
                    this.editing = true;
                    this.$nextTick(() => {
                        this.$refs.headline.focus();
                    });
                }
            },
            loggedInUserViewingOwnPage() {
                return this.loggedInUser.id && this.loggedInUser.id === this.user.id;
            },
            cancelEditing() {
                this.editing = false;
            },
            saveProfile() {
                this.editing = false;
                this.user.about = this.$refs.about.value;
                this.user.headline = this.$refs.headline.value;
                this.$toasted.show('Saved profile!', {duration: 5000, position: "bottom-right"});

                let auth = '?api_token=' + window.api_token;
                axios.post("/api/v1/users/" + this.user.username + auth, {
                    'data': this.user
                }).then(function(response) {
                    self.user = response.data;
                });
            },
        },
        computed: {
            compiledMarkdown: function () {
                let converter = new showdown.Converter();
                return converter.makeHtml(this.user.about);
            },
            hasUpvotes: function() {
                return this.user.upvotes && this.user.upvotes.length;
            },
            loggedInUser: function() {
                return window.user ? window.user : {};
            },
        }
    }
</script>
