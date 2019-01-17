<template>
    <div class="page page-profile">
        <top-nav>
            <div class="edit-profile-wrapper m-1 inline-block">
                <div v-if="editing" class="inline-block">
                    <a class="paragraph-link mr-3" @click="cancelEditing()" v-shortkey="['esc']" @shortkey="cancelEditing()">
                        Cancel
                    </a>
                    <a class="btn px-5 py-2" @click="saveProfile()" v-shortkey="['meta', 'enter']" @shortkey="saveProfile()">Save</a>
                </div>
                <div v-else class="inline-block">
                    <a class="btn px-5 py-2" @click="editProfile()">Edit Profile</a>
                </div>
            </div>
        </top-nav>
        <section class="header max-w-lg mx-auto text-center">
            <div class="avatar mb-1">
                <img v-bind:src="user.avatar_path">
            </div>
            <h1 class="mb-4">
                <span v-if="editing" class="editable-headline">
                    <textarea ref="headline" class="text-3xl text-center no-border w-full" >{{ user.headline }}</textarea>
                </span>
                <span v-else>{{ user.headline }}</span>
            </h1>
        </section>
        <div class="mx-auto max-w-md text-center">
            <user-profile-tags :user="user" :editing="editing"></user-profile-tags>
        </div>
        <div class="section mx-auto max-w-md">
            <div class="card">
                <div class="card--inner text-left">
                    <div class="editable-about" v-if="editing">
                        <textarea ref="about" class="font-90 width-100">{{ user.about }}</textarea>
                    </div>
                    <div v-else v-html="compiledMarkdown">{{ user.about }}</div>
                </div>
            </div>
        </div>
        <hr v-if="hasUpvotes" />
        <div v-if="hasUpvotes" class="section endorsements mx-auto max-w-sm">
            <div class="card hoverable endorsement-card" v-for="upvote in user.upvotes" :key="upvote.id">
                <div class="card--inner">
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
            editProfile() {
                this.editing = true;
                let self = this;
                this.$nextTick(function() {
                    self.$refs.headline.focus();
                });
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
