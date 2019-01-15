<template>
    <div class="container page page-profile">
        <div class="header centered pb-1">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
            <div class="avatar mb-1">
                <img v-bind:src="user.avatar_path">
            </div>
            <div class="edit-profile-wrapper m-1">
                <div v-if="editing">
                    <a class="paragraph-link mr-1" @click="cancelEditing()" v-shortkey="['esc']" @shortkey="cancelEditing()">
                        Cancel
                    </a>
                    <a class="btn" @click="saveProfile()">Save</a>
                </div>
                <div v-else>
                    <a class="btn" @click="editProfile()" v-shortkey="['e']" @shortkey="editProfile()">Edit Profile</a>
                </div>
            </div>
            <h1 class="mb-2">
                <span v-if="editing" class="editable-headline">
                    <textarea ref="headline" class="font-90 no-border width-100" >{{ user.headline }}</textarea>
                </span>
                <span v-else>{{ user.headline }}</span>
            </h1>
        </div>
        <div class="section centered margin-auto max-width-medium">
            <profile-tags v-bind:user="user" v-bind:editing="editing"></profile-tags>
        </div>
        <div class="section margin-auto max-width-medium">
            <div class="card">
                <div class="card--inner text-left">
                    <div class="editable-about" v-if="editing">
                        <textarea ref="about" class="font-90 width-100">{{ user.about }}</textarea>
                    </div>
                    <div v-else v-html="compiledMarkdown">{{ user.about }}</div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="section endorsements margin-auto max-width-medium">
            <div class="card hoverable endorsement-card" v-for="endorsement in user.endorsements">
                <div class="card--inner">
                    <div class="avatar centered mr-1">
                        <img v-bind:src="endorsement.user.avatar_url">
                        {{ endorsement.user.first_name }}
                    </div>
                    <div class="endorsement-message">
                        <div>
                            <p class="mb-05">
                                {{ endorsement.message }}
                            </p>
                            <div class="inline-tag">{{ endorsement.tag }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {},
                editing: false,
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users/' + this.$route.params.username).then(function(response) {
                self.user = response.data;
            });
        },
        methods: {
            editProfile() {
                this.editing = true;
            },
            cancelEditing() {
                this.editing = false;
            },
            saveProfile() {
                this.editing = false;
                this.user.about = this.$refs.about.value;
                this.user.headline = this.$refs.headline.value;
                this.$toasted.show('Saved profile!', {duration: 5000, position: "bottom-right"});
            },
        },
        computed: {
            compiledMarkdown: function () {
                let converter = new showdown.Converter();
                return converter.makeHtml(this.user.about);
            },
        }
    }
</script>
