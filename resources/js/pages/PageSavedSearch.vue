<template>
    <div class="page-saved-search" :class="{ 'can-edit' : canEdit }">
        <top-nav class="m-4 sm:m-8">
            <div v-if="editing" class="m-1 inline-block">
                <a class="paragraph-link mr-3" @click="editing=false">
                    Cancel
                </a>
                <a class="btn px-5 py-2 mr-3" @click="save()">Save</a>
            </div>
        </top-nav>
        <section class="header text-center max-w-lg mb-4 mx-4">
            <h1 class="text-2xl sm:text-4xl editable" @click="editIfOwner()">
                <template v-if="editing">
                    <input ref="name" class="text-3xl text-center no-border w-full"
                           v-model="savedSearch.name"
                           v-shortkey="['enter']" @shortkey="save()"
                    >
                </template>
                <template v-else>
                    {{ savedSearch.name }}
                    <i class="edit-icon fas fa-pencil-alt" v-if="canEdit"></i>
                </template>
            </h1>
            <template v-if="editing">
                <input ref="query" class="text-lg text-center no-border w-full"
                       v-model="savedSearch.query"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="slug" class="text-lg text-center no-border w-full" placeholder="slug"
                       v-model="savedSearch.slug"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="featured_order" class="text-lg text-center no-border w-full"
                       v-model="savedSearch.featured_order"
                       v-shortkey="['enter']" @shortkey="save()"
                >
            </template>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2"
                           v-for="user in savedSearch.users.slice(0, 12)"
                           :user="user" :key="user.id"
                />
            </div>
            <div class="centered" v-if="savedSearch.users.length > 12">
                <router-link class="btn px-5 py-2" :to="{name: 'search-query', params: { query: savedSearch.query }}">
                    See more
                    <i class="fas fa-caret-right ml-2"></i>
                </router-link>
            </div>
        </section>
        <keyboard-shortcuts></keyboard-shortcuts>
        <footer-component></footer-component>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                savedSearch: {users: []},
                editing: false,
            }
        },
        mounted() {
            axios.get('/api/v1/saved-searches/' + this.$route.params.slug).then((response) => {
                this.savedSearch = response.data;
            });
        },
        methods: {
            editIfOwner() {
                if (this.canEdit) {
                    this.editing = true;
                    this.$nextTick(() => {
                        this.$refs.name.focus();
                    });
                }
            },
            save() {
                this.editing = false;

                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.post('/api/v1/saved-searches/' + this.savedSearch.id + auth, {
                    name: this.savedSearch.name,
                    query: this.savedSearch.query,
                    featured_order: this.savedSearch.featured_order,
                    slug: this.savedSearch.slug
                }).then((response) => {
                    this.savedSearch = response.data;
                    this.$toasted.show("Saved!");
                });
            }
        },
        computed: {
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser: function() {
                return this.$store.state.user;
            },
            canEdit() {
                if (! this.loggedIn) {
                    return false;
                }

                return (this.loggedInUser.id === this.savedSearch.user_id);
            }
        }
    }
</script>
