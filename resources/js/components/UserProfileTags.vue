<template>
    <div class="relative">
        <!--<div class="tag"><i class="fa fa-location-arrow pr-05"></i> {{ user.city }}</div>-->

        <template v-if="loggedInUserViewingOwnPage()">
            <tag-editable v-for="tag in user.tags" :user="user" :tag="tag" :key="tag.id"></tag-editable>
            <a class="tag add-tag" @click="addTag()">
                <i class="fas fa-plus mr-02"></i> Add tag
            </a>
        </template>
        <template v-else >
            <tag-clickable v-for="tag in user.tags" :user="user" :tag="tag" :key="tag.id"></tag-clickable>
            <tag-endorsement user="user"></tag-endorsement>
        </template>

        <div class="tag-autocomplete card absolute" v-if="addingTag">
            <div class="card--inner pt-3">
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
    // import 'vuetify/dist/vuetify.min.css'; todo this is killing my css ugh

    export default {
        props: ['user', 'editing'],
        data() {
            return {
                addingTag: false,
                tags: [],
                tagSearch: null,
                model: null,
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/tags').then(function(response) {
                self.tags = response.data;
            });
        },
        methods: {
            addTag() {
                this.addingTag = true;
            },
            tagInput: function(data) {
                let auth = '?api_token=' + window.api_token;
                axios.post("/api/v1/users/" + this.user.username + "/add-tag" + auth, {
                    'tag': this.model
                }).then((response) => {
                    this.user.tags = response.data;
                });
                this.addingTag = false;
            },
            loggedInUserViewingOwnPage() {
                return this.loggedInUser.id && this.loggedInUser.id === this.user.id;
            }
        },
        computed: {
            tagNames: function() {
                return this.tags.map( tag => tag.name);
            },
            loggedInUser: function() {
                return window.user ? window.user : {};
            }
        }
    }
</script>
