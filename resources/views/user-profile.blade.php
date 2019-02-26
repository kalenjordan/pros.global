@extends('_layouts.base')

<?php
/** @var \App\User $user */
?>

@section('title')
    <title>{{ $user->name }} | pros.global</title>
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => $user->name,
        'description' => $user->headline,
        'image' => env('APP_URL') . "/" . $user->username . "/twitter-card",
        'version' => 'v1',
    ])
@stop

@section('content')
    <div class="page page-profile" id="profile" :class="{ 'can-edit' : canEdit }">
        <top-nav class="m-4 sm:m-8" :user="user">
            <div v-if="editing" class="edit-profile-wrapper m-1 inline-block">
                <div class="inline-block mr-3">
                    <a class="paragraph-link mr-3" @click="cancelEditing()">
                        Cancel
                    </a>
                    <a class="btn px-5 py-2" @click="save">Save</a>
                </div>
            </div>
            <div v-if="canEdit && !editing">
                <div class="mr-6" @click="editing=1">
                    <i class="material-icons font-120 cursor-pointer animated">edit</i>
                </div>
            </div>
        </top-nav>
        <section class="header max-w-lg mx-auto text-center">
            <div class="m-4">
                <div class="avatar inline-block mb-1 relative">
                    <img src="{{ $user->avatar_path }}" class="w-16 sm:w-32 h-16 sm:h-32 rounded-full">
                    <!--<i v-if="this.isPresent(user)" class="absolute is-present fas fa-circle"></i>-->
                </div>
                <textarea cols=3 ref="headline" v-if="editing" v-text="user.headline"
                          class="p-2 mb-2  block mx-auto w-full bg-transparent-input text font-150"
                          placeholder="e.g. I am a person that does certain things!"></textarea>
                <input ref="avatar_path" v-if="editing" v-model="user.avatar_path"
                       class="p-2 mb-2 block mx-auto w-128 text bg-transparent-input"
                       placeholder="e.g. path to avatar">
                <input ref="name" v-if="editing" v-model="user.name"
                       class="p-2 mb-2  block mx-auto w-128 bg-transparent-input text"
                       placeholder="e.g. Jane Smith">
                <input ref="twitter_username" v-if="editing" v-model="user.twitter_username"
                       class="p-2 mb-2  block mx-auto w-64 bg-transparent-input text"
                       placeholder="e.g. username">
                <h1 v-if="!editing" class="text-xl sm:text-4xl animated" v-text="user.headline">
                    {{ $user->headline }}
                </h1>
            </div>
        </section>
        {{--<section class="mx-auto max-w-md text-center">--}}
        {{--<div class="m-4">--}}
        {{--<profile-tags :user="user" :editing="editing"></profile-tags>--}}
        {{--</div>--}}
        {{--</section>--}}
        <div class="section mx-auto max-w-md text-md" v-if="user.about || editing">
            <div class="card m-4">
                <div class="card--inner text-left p-4">
                    <div class="editable-about" v-if="editing">
                        <textarea ref="about" class="font-90 width-100">@{{ user.about }}</textarea>
                    </div>
                    <div v-else>
                        {!! Markdown::convertToHtml($user->about) !!}
                    </div>
                </div>
            </div>
        </div>
        {{--<section v-if="hasUpvotes" class="endorsements mx-auto p-4 max-w-sm text-sm leading-tight">--}}
        {{--<div class="card hoverable endorsement-card mb-4" v-for="upvote in user.upvotes" :key="upvote.id">--}}
        {{--<div class="card--inner p-4 flex">--}}
        {{--<div class="avatar centered text-center -ml-3">--}}
        {{--<router-link :to="{ path: '/' + upvote.author_username }">--}}
        {{--<img v-bind:src="upvote.author_avatar" class="w-8 h-8 rounded-full">--}}
        {{--</router-link>--}}
        {{--</div>--}}
        {{--<div class="endorsement-message flex-4 sm:flex-6">--}}
        {{--<div>--}}
        {{--<div v-if="upvote.message" class="mb-2" v-html="markdown(upvote.message)"></div>--}}
        {{--<div v-else class="mb-2">{{ upvote.author_firstname }} upvoted</div>--}}
        {{--<div class="inline-tag">{{ upvote.tag_name }}</div>--}}
        {{--<router-link v-if="loggedInUser.id === upvote.author_id"--}}
        {{--:to="{ path: '/upvotes/' + upvote.id + '?editing=1' }">--}}
        {{--<i class="edit-upvote material-icons align-middle">edit</i>--}}
        {{--</router-link>--}}
        {{--<div class="inline text-gray-light">--}}
        {{--<router-link class="naked-link text-xs ml-1"--}}
        {{--:to="{ path: 'upvotes/' + upvote.id }">--}}
        {{--{{ upvote.created_at | moment("subtract", "6 hours") | moment('from') }}--}}
        {{--</router-link>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</section>--}}
        <section class="mt-16 text-center text-4xl text-gray-light">
            <a class="naked-link mr-3" target="_blank"
               href="https://www.linkedin.com/search/results/all/?keywords={{ $user->name }}">
                <i class="fab fa-linkedin"></i>
            </a>
            @if ($user->twitter_username)
                <a class="naked-link" target="_blank"
                   href="https://twitter.com/{{ $user->twitter_username }}">
                    <i class="fab fa-twitter"></i>
                </a>
            @endif
        </section>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">
        pageComputed = {
            canEdit() {
                if (!this.loggedIn) {
                    return false;
                }

                if (this.loggedInUser.is_admin) {
                    return true;
                }

                return (this.loggedInUser.id === this.user.id);
            },
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser() {
                return this.$store.state.user;
            },
        };

        pageData = {
            user: { {!! substr(json_encode($user->toArray()), 1, strlen(json_encode($user->toArray())) - 2) !!} },
            editing: false,
            messages: [],
        };

        pageMounted = function (Vue) {
            window.addEventListener('keyup', Vue.hotkeys);
        };

        pageMethods = {
            editIfOwner() {
                if (this.canEdit) {
                    this.editing = true;
                    this.$nextTick(() => {
                        if (this.$refs.headline) {
                            this.$refs.headline.focus();
                        }
                    });
                }
            },
            cancelEditing() {
                this.editing = false;
            },
            save() {
                this.editing = false;
                this.user.about = this.$refs.about.value;
                this.user.headline = this.$refs.headline.value;
                // Don't need to set avatar_path, headline or name because of v-model

                axios.post(this.api("users/" + this.user.username), {
                    'data': this.user
                }).then((response) => {
                    this.$toasted.show('Saved profile!');
                });
            },
            markdown: function (content) {
                // let converter = new showdown.Converter();
                // return converter.makeHtml(content);
            },
            hotkeys(e) {
                if (e.key === 'Escape') {
                    this.editing = false;
                }

                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'i') {
                        window.location = '/admin/impersonate/' + this.user.username;
                    }
                    if (e.key === 'e') {
                        this.editIfOwner();
                    }
                }
            },

            api(path) {
                path = '/api/v1/' + path;
                if (this.loggedInUser) {
                    path = path + (path.indexOf('?') !== -1 ? '&' : '?') + 'api_token=' + this.loggedInUser.api_token;
                }

                return path;
            },

            // isPresent(user) {
            //     let ids = [];
            //     let presentUsers = this.presentUsers;
            //     for (let i in presentUsers) {
            //         ids.push(presentUsers[i].id);
            //     }
            //     return ids.includes(user.id);
            // },
        };

    </script>
@stop