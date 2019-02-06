<template>
    <div class="relative">
        <template v-if="loggedInUserViewingOwnPage()">
            <tag-editable v-for="tag in user.tags" :user="user" :tag="tag" :key="tag.id"></tag-editable>
        </template>
        <template v-else >
            <tag-clickable v-for="tag in user.tags" :user="user" :tag="tag" :key="tag.id"></tag-clickable>
            <tag-endorsement user="user"></tag-endorsement>
        </template>
        <div class="tag add-tag" @click="addTag()">
            <span class="tag-name">
                <i class="tag-icon fas fa-plus"></i> Add tag
            </span>
        </div>

        <div class="tag-autocomplete card absolute" v-if="isAddingTag">
            <div class="card--inner p-4">
                <v-combobox
                        v-model="model"
                        :items="tagNames"
                        :search-input.sync="tagSearch"
                        hide-selected
                        autofocus
                        solo
                        persistent-hint
                        small-chips
                        return-object
                        @input="tagInput()"
                        @blur="isAddingTag=0"
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
    </div>
</template>

<script>
    export default {
        props: ['user', 'editing'],
        data() {
            return {
                isAddingTag: false,
                tags: [],
                tagSearch: null,
                model: null,
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeys);
            axios.get('/api/v1/tags').then((response) => {
                this.tags = response.data;
            });
        },
        methods: {
            addTag() {
                if (! this.loggedIn) {
                    return alert("Please login first before you can add tags");
                }
                this.isAddingTag = true;
            },
            hotkeys(e) {
                if (document.activeElement.tagName === 'INPUT') {
                    if (e.key === 'Escape') {
                        this.isAddingTag = false;
                    }
                } else if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 't') {
                        this.addTag();
                    }
                }
            },
            tagInput: function(data) {
                let auth = '?api_token=' + this.loggedInUser.api_token;

                axios.post("/api/v1/users/" + this.user.username + "/add-tag" + auth, {
                    'tag': this.model
                }).then((response) => {
                    this.user.tags = response.data;
                });
                this.isAddingTag = false;
            },
            loggedInUserViewingOwnPage() {
                return this.loggedInUser.id && this.loggedInUser.id === this.user.id;
            },
        },
        computed: {
            tagNames: function() {
                return this.tags.map( tag => tag.name);
            },
            loggedIn: function() {
                return this.$store.state.user.id;
            },
            loggedInUser: function() {
                return this.$store.state.user;
            }
        }
    }
</script>
