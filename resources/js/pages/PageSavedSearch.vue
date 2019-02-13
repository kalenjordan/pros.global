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
        <section class="header text-center max-w-lg mx-auto mb-4">
            <h1 class="mx-4 text-2xl sm:text-4xl editable" @click="editIfOwner()">
                <template v-if="editing">
                    <input ref="name" class="text-3xl text-center no-border w-full"
                           v-model="savedSearch.name"
                           v-shortkey="['enter']" @shortkey="save()"
                    >
                </template>
                <template v-else>
                    {{ savedSearch.description ? savedSearch.description : savedSearch.name  }}
                    <i class="edit-icon fas fa-pencil-alt" v-if="canEdit"></i>
                </template>
            </h1>
            <template v-if="editing">
                <input ref="description" class="text-lg text-center no-border w-full"
                       v-model="savedSearch.description"
                       placeholder="Longer description (also serves as meta description tag)"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="query" class="text-lg text-center no-border w-full"
                       v-model="savedSearch.query"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="slug" class="text-lg text-center no-border w-full" placeholder="slug"
                       v-model="savedSearch.slug"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="featured_order" class="text-lg text-center no-border w-full" placeholder="e.g. 10"
                       v-model="savedSearch.featured_order"
                       v-shortkey="['enter']" @shortkey="save()"
                >
                <input ref="icon" class="text-lg text-center no-border w-full" placeholder="e.g. fas fa-location-arrow"
                       v-model="savedSearch.icon"
                       v-shortkey="['enter']" @shortkey="save()"
                >
            </template>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <user-card class="hoverable w-full sm:max-w-xs m-2"
                           v-for="user in savedSearch.users.slice(0, 6)"
                           :user="user" :key="user.id"
                />
            </div>
            <div class="centered" v-if="savedSearch.users.length > 6">
                <router-link class="btn px-5 py-2" :to="{name: 'search-query', params: { query: savedSearch.query }}">
                    See more
                    <i class="fas fa-caret-right ml-2"></i>
                </router-link>
            </div>
        </section>

        <hr v-if="relatedSavedSearches.length" class="mt-16 mb-16"/>
        <section v-if="relatedSavedSearches.length" class="max-w-3xl mb-8 mx-auto">
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                <saved-search-card class="mb-12 m-4" v-for="savedSearch in relatedSavedSearches"
                                   :key="savedSearch.id"
                                   :savedSearch="savedSearch"/>
            </div>
        </section>

        <div v-if="editing">
            <input ref="relatedSavedSearchSlug" placeholder="Add related saved search (by slug)"
                   @blur="addRelatedSavedSearch()"
                   class="w-128 mx-auto my-2 p-2 block">
            <input ref="relatedToRemove" placeholder="Remove related saved search (by slug)"
                   @blur="removeRelatedSavedSearch()"
                   class="w-128 mx-auto my-2 p-2 block">
        </div>

        <hr class="mt-16 mb-16"/>
        <section class="max-w-lg mb-8 mx-auto p-4 text-center">
            <h2 class="mb-4">Want to be added to this list?</h2>
            <div v-if="!this.loggedIn">
                <a class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Sign up for free</a>
            </div>
            <div v-else class="text-xl">
                <p>
                    If you want to be added to this list and aren't on it already, just
                    <router-link :to="{name: 'profile', params: {username: loggedInUser.username}}">
                        tag your profile
                    </router-link>
                    with the tags that this list is associated with.
                </p>
            </div>
        </section>
        <hr class="mt-16 mb-16"/>
        <keyboard-shortcuts/>
        <footer-component/>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                savedSearch: {users: []},
                relatedSavedSearches: [],
                editing: false,
            }
        },
        mounted() {
            axios.get('/api/v1/saved-searches/' + this.$route.params.slug).then((response) => {
                this.savedSearch = response.data;
            });
            axios.get('/api/v1/saved-searches/' + this.$route.params.slug + '/related?with_users=1')
                .then((response) => {
                    this.relatedSavedSearches = response.data;
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

                axios.post('/api/v1/saved-searches/' + this.savedSearch.id + '?' + this.auth, {
                    name: this.savedSearch.name,
                    description: this.savedSearch.description,
                    query: this.savedSearch.query,
                    featured_order: this.savedSearch.featured_order,
                    icon: this.savedSearch.icon,
                    slug: this.savedSearch.slug,
                }).then((response) => {
                    this.savedSearch = response.data;
                    this.$toasted.show("Saved!");
                });
            },
            addRelatedSavedSearch() {
                axios.post('/api/v1/saved-searches/' + this.savedSearch.id + '/related?' + this.auth, {
                    slug: this.$refs.relatedSavedSearchSlug.value,
                }).then((response) => {
                    this.relatedSavedSearches = response.data;
                    this.$toasted.show("Saved new related saved search!");
                });
            },
            removeRelatedSavedSearch() {
                axios.post('/api/v1/saved-searches/' + this.savedSearch.id + '/related/remove?' + this.auth, {
                    slug: this.$refs.relatedToRemove.value,
                }).then((response) => {
                    this.relatedSavedSearches = response.data;
                    this.$toasted.show("Saved new related saved search!");
                });
            },
        },
        computed: {
            auth() {
                return 'api_token=' + this.loggedInUser.api_token;
            },
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser: function () {
                return this.$store.state.user;
            },
            canEdit() {
                if (!this.loggedIn) {
                    return false;
                }

                if (this.loggedInUser.is_admin) {
                    return true;
                }

                return (this.loggedInUser.id === this.savedSearch.user_id);
            }
        },
        metaInfo() {
            return {
                title: this.unreadNotificationCount ? '(' + this.unreadNotificationCount + ') ' : '' +
                    (this.savedSearch ? this.savedSearch.name + " | " : "")
                    + "pros.global",
            }
        },
    }
</script>
