@extends('_layouts.base')

<?php

/** @var $savedSearch \App\SavedSearch */
/** @var \App\User $user */
/** @var \App\Tagged $tagged */

$users = $savedSearch->fetchUsers();
$related = $savedSearch->relatedSavedSearches();

?>

@section('title')
    <title>{{ $savedSearch->description ? $savedSearch->description : $savedSearch->name }}</title>
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => $savedSearch->name,
        'description' => $savedSearch->description,
        'image' => env('APP_URL') . "/s/" . $savedSearch->getSlugOrId() . "/twitter-card",
        'version' => 'v1',
    ])
@stop

@section('content')
    <div class="page-saved-search" :class="{ 'can-edit' : canEdit }">
        <top-nav class="m-4 sm:m-8 hidden-before-vue">
            <div v-if="editing" class="m-1 inline-block">
                <a class="paragraph-link mr-3" @click="editing=false">
                    Cancel
                </a>
                <a class="btn px-5 py-2 mr-3" @click="save()">Save</a>
            </div>
            <div v-if="canEdit && !editing">
                <div class="mr-6" @click="editing=1">
                    <i class="material-icons font-120 cursor-pointer animated">edit</i>
                </div>
            </div>
        </top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4">
            <h1 class="mx-4 text-2xl sm:text-4xl editable" @click="editIfOwner()">
                <template v-if="editing">
                    <input ref="name" class="text-3xl text-center w-full text bg-transparent-input mb-2"
                           v-model="savedSearch.name"
                    >
                </template>
                <template v-else>
                    {{ $savedSearch->description ? $savedSearch->description : $savedSearch->name }}
                </template>
            </h1>
            <template v-if="editing">
                <input ref="description"
                       class="text-lg text-center no-border block w-full mx-auto mb-2 text bg-transparent-input"
                       v-model="savedSearch.description"
                       placeholder="Longer description (also serves as meta description tag)"
                >
                <input ref="query" class="text-lg text-center no-border block w-64 text mx-auto mb-2 bg-transparent-input"
                       v-model="savedSearch.query"
                >
                <input ref="slug"
                       class="text-lg text-center no-border w-64 block mx-auto mb-2 text bg-transparent-input"
                       placeholder="slug"
                       v-model="savedSearch.slug"
                >
                <input ref="featured_order"
                       class="text-lg text-center no-border w-24 text bg-transparent-input block mx-auto mb-2"
                       placeholder="e.g. 10"
                       v-model="savedSearch.featured_order"
                >
                <input ref="icon" class="text-lg text-center no-border text bg-transparent-input block mx-auto mb-2 w-64"
                       placeholder="e.g. fas fa-location-arrow"
                       v-model="savedSearch.icon"
                >
            </template>
        </section>
        @if ($users && $users->count())
            <section class="max-w-2xl mb-8 mx-auto">
                <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                    @foreach ($users->limit(12)->get() as $savedSearchUser)
                        @include ('partials.user-card', ['user' => $savedSearchUser, 'css' => 'hoverable w-full sm:max-w-xs m-2'])
                    @endforeach
                </div>
                @if ($users->count() > 12)
                    <div class="centered">
                        <a class="btn px-5 py-2 bold" href="/search?q={{ $savedSearch->query }}">
                            See more
                            <i class="material-icons align-middle" style="margin-right: -7px;">keyboard_arrow_right</i>
                        </a>
                    </div>
                @endif
            </section>
        @endif

        @if ($related->count())
            <hr class="mt-16 mb-16"/>
            <section class="max-w-3xl mb-8 mx-auto">
                <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                    @foreach ($related->get() as $relatedSavedSearch)
                        @include ('partials.saved-search-card', ['savedSearch' => $relatedSavedSearch, 'css' => 'mb-12 m-4'])
                    @endforeach
                </div>
            </section>
        @endif

        <div v-if="editing">
            <input ref="relatedSavedSearchSlug" id="relatedSavedSearchSlug" placeholder="Add related saved search (by slug)"
                   class="w-128 mx-auto my-2 p-2 block text bg-transparent-input">
            <input ref="relatedToRemove" id="relatedToRemove" placeholder="Remove related saved search (by slug)"
                   class="w-128 mx-auto my-2 p-2 block text bg-transparent-input">
        </div>

        <hr class="mt-16 mb-16"/>
        <section class="max-w-lg mb-8 mx-auto p-4 text-center">
            <h2 class="mb-4">Should someone be added to this list?</h2>
            <div v-if="!this.loggedIn">
                <a class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Sign up for free</a>
            </div>
            <div v-else class="text-xl">
                @if (Auth::user())
                    <p>
                        If you or someone that you know should be on this list, you can add them.
                        To add yourself, just
                        <a href="/{{ Auth::user()->username }}">
                            tag your profile
                        </a>
                        with the tags that this list is associated with. If there is someone who isn't
                        already on pros.global, you can
                        <a href="https://pros.global/kalenjordan/posts/add-someone-else-to-pros.global-using-their-twitter-handle">
                            add them using their twitter handle
                        </a>
                        then tag them.
                    </p>
                @endif
            </div>
        </section>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">
        pageData = {
            savedSearch: { {!! \App\Util::jsonEncodeWithoutBrackets($savedSearch->toArray()) !!} },
            relatedSavedSearches: [ {!! \App\Util::jsonEncodeWithoutBrackets($related->get()) !!} ],
            editing: false,
        };

        pageMounted = function (Vue) {
            window.addEventListener('keydown', Vue.hotkeys);

            let list = document.querySelectorAll('.hidden-before-vue');
            for (let i = 0; i < list.length; ++i) {
                list[i].classList.remove('hidden-before-vue');
            }
        };

        pageMethods = {
            hotkeys(e) {
                if (e.key === 'Escape') {
                    this.editing = false;
                }

                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'e') {
                        e.preventDefault();
                        this.editIfOwner();
                    }
                }

                if (e.key === 'Enter') {
                    if (document.activeElement.id === 'relatedSavedSearchSlug') {
                        this.addRelatedSavedSearch();
                    }

                    if (document.activeElement.id === 'relatedToRemove') {
                        this.removeRelatedSavedSearch();
                    }
                }

                if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                    if (e.key === 'Enter' && e.metaKey) {
                        e.preventDefault();
                        this.save();
                    }
                }
            },
            editIfOwner() {
                if (this.canEdit) {
                    this.editing = true;
                    this.$nextTick(() => {
                        if (this.$refs.name) {
                            this.$refs.name.focus();
                        }
                    });
                }
            },
            save() {
                this.editing = false;

                axios.post(this.api('saved-searches/' + this.savedSearch.id), {
                    name: this.savedSearch.name,
                    description: this.savedSearch.description,
                    query: this.savedSearch.query,
                    featured_order: this.savedSearch.featured_order,
                    icon: this.savedSearch.icon,
                    slug: this.savedSearch.slug,
                }).then((response) => {
                    this.savedSearch = response.data;
                    this.$toasted.show("Saved!", {
                        action: {
                            text: 'View',
                            href: response.data.url,
                        },
                    });
                });
            },
            addRelatedSavedSearch() {
                axios.post(this.api('saved-searches/' + this.savedSearch.id + '/related'), {
                    slug: this.$refs.relatedSavedSearchSlug.value,
                }).then((response) => {
                    // this.relatedSavedSearches = response.data;
                    this.$toasted.show("Saved new related saved search!");
                });
            },
            removeRelatedSavedSearch() {
                axios.post(this.api('saved-searches/' + this.savedSearch.id + '/related/remove'), {
                    slug: this.$refs.relatedToRemove.value,
                }).then((response) => {
                    // this.relatedSavedSearches = response.data;
                    this.$toasted.show("Removed related saved search!");
                });
            },
            api(path) {
                path = '/api/v1/' + path;
                if (this.loggedInUser) {
                    path = path + (path.indexOf('?') !== -1 ? '&' : '?') + 'api_token=' + this.loggedInUser.api_token;
                }

                return path;
            },
        };

        pageComputed = {
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
        };

    </script>
@stop