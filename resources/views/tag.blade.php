@extends('_layouts.base')

<?php

/** @var \App\Tag $tag */

$users = $tag->users();

?>

@section('title')
    <title>Tag: {{ $tag->name }}</title>
    <link rel="canonical" href="{{ env('APP_URL') }}/tag/{{ $tag->slug }}">
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => $tag->name,
        'description' => "Pros that are tagged with: " . $tag->name,
        'image' => env('APP_URL') . "/tag/" . $tag->slug . "/twitter-card",
        'version' => 'v1',
    ])
@stop

@section('content')
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
                    <input ref="name" class="text-3xl text-center no-border w-full bg-transparent-input text mb-2"
                           v-model="tag.name"
                    >
                </template>
                <template v-else>
                    Tag: {{ $tag->name }}
                </template>
            </h1>
            <template v-if="editing">
                <input ref="slug" class="text-lg text-center no-border block mx-auto w-64 bg-transparent-input text mb-2"
                       placeholder="slug"
                       v-model="tag.slug"
                >
                <input ref="icon" class="text-lg text-center no-border block mx-auto w-64 bg-transparent-input text mb-2"
                       placeholder="e.g. fas fa-location-arrow"
                       v-model="tag.icon"
                >
            </template>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                @foreach ($users->get() as $taggedUser)
                    @include ('partials.user-card', ['user' => $taggedUser, 'css' => 'hoverable w-full sm:max-w-xs m-2'])
                @endforeach
            </div>
            @if ($users->count() > 24)
                <div class="centered">
                    <a class="btn px-5 py-2" href="/search?q=tag:{{ $tag->slug }}">
                        See more
                        <i class="fas fa-caret-right ml-2"></i>
                    </a>
                </div>
            @endif
        </section>
        <section class="max-w-lg mb-8 mx-auto p-4 text-center">
            <h2 class="mb-4">Want to be added to this list?</h2>
            <div v-if="!this.loggedIn">
                <a class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Sign up for free</a>
            </div>
            <div v-else class="text-xl">
                @if (Auth::user())
                    <p>
                        If you want to be added to this list and aren't on it already, just
                        <a href="/{{ Auth::user()->username }}">
                            tag your profile
                        </a>
                        with the tags that this list is associated with.
                    </p>
                @endif
            </div>
        </section>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">
        pageData = {
            tag: { {!! \App\Util::jsonEncodeWithoutBrackets($tag->toArray()) !!} },
            editing: false,
        };

        pageMounted = function (Vue) {
            window.addEventListener('keydown', Vue.hotkeys);
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
                        this.$refs.name.focus();
                    });
                }
            },
            save() {
                this.editing = false;

                axios.post(this.api('tag/' + this.tag.slug), {
                    name: this.tag.name,
                    slug: this.tag.slug,
                    icon: this.tag.icon,
                }).then((response) => {
                    this.$toasted.show("Saved tag!");
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

                return !!this.loggedInUser.is_admin;
            },
        };

    </script>
@stop