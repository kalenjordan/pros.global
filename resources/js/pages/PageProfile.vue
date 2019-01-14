<template>
    <div class="container page page-profile">
        <div class="header centered pb-1">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
            <div class="avatar mb-1">
                <img v-bind:src="user.image_url">
            </div>
            <div class="edit-profile-wrapper m-1">
                <div v-if="editing">
                    <a class="paragraph-link mr-1" @click="cancelEditing()">Cancel</a>
                    <a class="btn" @click="saveProfile()">Save</a>
                </div>
                <div v-else>
                    <a class="btn" @click="editProfile()">Edit Profile</a>
                </div>
            </div>
            <h1>
                <span v-if="editing" class="editable-headline">
                    <textarea ref="headline" class="font-90 no-border width-100" >{{ user.headline }}</textarea>
                </span>
                <span v-else>{{ user.headline }}</span>
            </h1>
        </div>
        <div class="section centered margin-auto">
            <div class="tag"><i class="fa fa-location-arrow pr-05"></i> {{ user.city }}</div>

            <template v-if="editing">
                <tag-editable v-for="tag in user.tags" v-bind:tag="tag" :key="tag.id"></tag-editable>
                <a class="tag add-tag" @click="addTag()">
                    <i class="fas fa-plus mr-02"></i> Add tag
                </a>
            </template>
            <template v-else >
                <tag-clickable v-for="tag in user.tags" v-bind:tag="tag"></tag-clickable>
                <tag-endorsement></tag-endorsement>
            </template>

            <div class="tag-autocomplete relative" v-if="addingTag">
                <v-combobox
                        v-model="model"
                        :items="tagNames"
                        :search-input.sync="tagSearch"
                        hide-selected
                        hint="Maximum of 5 tags"
                        label="Add some tags"
                        persistent-hint
                        small-chips
                        return-object
                        @input="tagInput()"
                >
                    <template slot="no-data">
                        <v-list-tile>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    No results matching "<strong>{{ tagSearch }}</strong>". Press <kbd>enter</kbd> to create a new one
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </template>
                </v-combobox>
            </div>
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
    import 'vuetify/dist/vuetify.min.css';

    export default {
        data() {
            return {
                user: {},
                editing: false,
                addingTag: false,
                tags: [],
                tagSearch: null,
                model: null,
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users/1').then(function(response) {
                self.user = response.data;
            });
            axios.get('/api/v1/tags').then(function(response) {
                self.tags = response.data;
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
            addTag() {
                this.addingTag = true;
            },
            tagInput: function(data) {
                let self = this;
                this.addingTag = false;
                this.user.tags.push({
                    id: 99,
                    tag: self.model,
                    count: 0,
                    is_upvoted: 0
                });
            },
        },
        computed: {
            compiledMarkdown: function () {
                let converter = new showdown.Converter();
                return converter.makeHtml(this.user.about);
            },
            tagNames: function() {
                return this.tags.map( tag => tag.tag);
            }
        }
    }
</script>
