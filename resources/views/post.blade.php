@extends('_layouts.base')

<?php

/** @var \App\User $user */
/** @var \App\User $author */
/** @var \App\Post $post */

?>

@section('title')
    <title>{{ $post->title }} | pros.global</title>
    <link rel="canonical" href="{{ $post->url() }}">
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => $post->title,
        'description' => null,
        'image' => $post->url() . "/twitter-card",
    ])
@stop

@section('content')
    <div class="page page-post" :class="{ 'can-edit' : canEdit }">
        <top-nav class="m-4 sm:m-8 sm:-mb-4 hidden-before-vue">
            <div v-if="editing" class="edit-profile-wrapper m-1 inline-block">
                <div class="inline-block mr-3">
                    <a class="paragraph-link mr-3" @click="cancelEditing()">
                        Cancel
                    </a>
                    <a class="mr-3 btn px-5 py-2" @click="save">Save</a>
                    <a v-if="post.published_at" class="btn px-5 py-2" @click="unpublish">Unpublish</a>
                    <a v-if="!post.published_at" class="btn px-5 py-2" @click="publish">Publish</a>
                </div>
            </div>
            <div v-if="canEdit && !editing">
                <div class="mr-6" @click="editing=1">
                    <i class="material-icons font-120 cursor-pointer animated">edit</i>
                </div>
            </div>
        </top-nav>
        <section class="header max-w-lg mx-auto text-center">
            <div class="m-4 mb-8">
                <div class="avatar inline-block mb-4 relative">
                    <a href="/{{ $author->username }}">
                        <img src="{{ $author->avatar_path }}" class="w-20 avatar border-4">
                    </a>
                </div>
                <div class="hidden-before-vue">
                    <textarea cols=3 ref="title" v-if="editing" v-model="post.title"
                              class="text-center p-2 mb-4 block mx-auto w-full bg-transparent-input text font-150"
                              placeholder="e.g. Interesting title of blog post"></textarea>
                </div>
                <h1 v-if="!editing" class="text-2xl sm:text-4xl animated" v-text="post.title">
                    {{ $post->title() }}
                </h1>
                <div class="inline-block mx-auto mt-4 bg-gray-light p-2 px-4 hidden-before-vue rounded"
                     v-if="!post.published_at">Draft</div>
            </div>
        </section>
        <div class="section mx-auto max-w-md text-md hidden-before-vue">
            <div class="card content-card sm:m-4 sm:mb-8">
                <div class="card--inner text-left p-4 sm:p-8">
                    <div class="editable-about" v-if="editing">
                        <textarea ref="body" rows="20" class="font-90 width-100" v-model="post.body">@{{ post.body }}</textarea>
                    </div>
                    <div v-else v-html="markdown(post.body)">
                        {!! Markdown::convertToHtml($post->body) !!}
                    </div>
                </div>
            </div>
            <div>
                <input ref="slug" v-if="editing" v-model="post.slug"
                       class="p-2 mb-4  block mx-auto w-full sm:w-124 bg-transparent-input text"
                       placeholder="e.g. interesting-title-of-blog-post">
            </div>
        </div>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">

        pageData = {
            post: { {!! \App\Util::jsonEncodeWithoutBrackets($post->toArray()) !!} },
            editing: false,
        };

        pageMounted = function (Vue) {
            let list = document.querySelectorAll('.hidden-before-vue');
            for (let i = 0; i < list.length; ++i) {
                list[i].classList.remove('hidden-before-vue');
            }

            window.addEventListener('keydown', Vue.hotkeys);
        };

        pageMethods = {
            editIfOwner() {
                if (this.canEdit) {
                    this.editing = true;
                    this.$nextTick(() => {
                        if (this.$refs.title) {
                            this.$refs.title.focus();
                        }
                    });
                }
            },
            cancelEditing() {
                this.editing = false;
            },
            save() {
                this.editing = false;
                // Don't need to set title or slug b/c of v-model

                axios.post(this.api("posts/" + this.post.id), {
                    'data': this.post
                }).then((response) => {
                    this.post = response.data;
                    this.$toasted.show("Saved post!", {
                        action : {
                            text : 'View',
                            href: response.data.url,
                        },
                    });
                });
            },
            publish() {
                this.post.published_at = this.$moment().format("YYYY-MM-DD");
                this.save();
            },
            unpublish() {
                this.post.published_at = null;
                this.save();
            },
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
            markdown: function (content) {
                let converter = new showdown.Converter();
                return converter.makeHtml(content);
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
            canEdit() {
                if (!this.loggedIn) {
                    return false;
                }

                if (this.loggedInUser.is_admin) {
                    return true;
                }

                return (this.loggedInUser.id === this.post.user_id);
            },
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser() {
                return this.$store.state.user;
            },
        };
    </script>
@stop