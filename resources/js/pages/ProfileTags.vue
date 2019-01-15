<template>
    <div>
        <!--<div class="tag"><i class="fa fa-location-arrow pr-05"></i> {{ user.city }}</div>-->

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
                    autofocus
                    solo
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
</template>

<script>
    import 'vuetify/dist/vuetify.min.css';

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
                let self = this;
                axios.post("/api/v1/users/" + this.$parent.user.username + "/add-tag", {
                    'tag': self.model
                }).then(function(response) {
                    self.user.tags = response.data;
                });
                this.addingTag = false;
            },
        },
        computed: {
            tagNames: function() {
                return this.tags.map( tag => tag.name);
            }
        }
    }
</script>
