@extends('_layouts.base')

<?php

/** @var \App\TaggedUpvote $upvote */
?>

@section('title')
    <title>
        Shout-out to {{ $upvote->user->getFirstName() }} from {{ $upvote->tagged_user->getFirstName() }}
        | pros.global
    </title>
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => "Shout-out to " . $upvote->user->getFirstName() . " from " . $upvote->tagged_user->getFirstName(),
        'description' => substr($upvote->message, 0, 150),
        'image' => env('APP_URL') . "/upvotes/" . $upvote->id . "/twitter-card",
        'version' => 'v1',
    ])
@stop

@section('content')
    <div class="page page-upvote">
        <top-nav class="m-4 sm:m-8">
            <div v-if="editing" class="edit-profile-wrapper m-1 inline-block">
                <div class="inline-block">
                    <a class="paragraph-link mr-3" @click="cancel()">
                        Cancel
                    </a>
                    <a class="btn px-5 py-2" @click="save()">Save</a>
                </div>
            </div>
        </top-nav>
        <section class="hero text-center max-w-lg m-4 mx-auto">
            <div class="avatar">
                <a href="/{{ $upvote->tagged_user->username }}">
                    <img src="{{ $upvote->tagged_user->avatar_path }}"
                         class="w-16 sm:w-32 h-16 sm:h-32 rounded-full border-4 border-secondary-light
                         hover:border-secondary"
                    >
                </a>
            </div>
            <div class="inline-block" @click="editIfOwner()">
                {{--{{ $upvote->headline }}--}}
            </div>
        </section>
        <section class="mx-auto p-4 max-w-sm text-md">
            <div class="card mb-8">
                <div class="card--inner text-left p-4 leading-normal">
                    <div v-if="editing">
                        <textarea ref="message" class="width-100" rows="30">{{ $upvote->message }}</textarea>
                    </div>
                    <div v-else @click="editIfOwner()">
                        @if ($upvote->message)
                            {!! Markdown::convertToHtml($upvote->message) !!}
                        @else
                            {{ $upvote->user->getFirstName() }} upvoted
                            {{ $upvote->tagged_user->getFirstName() }} for
                            {{ $upvote->tagName() }}
                        @endif
                    </div>
                    <div class="inline-tag mt-2">{{ $upvote->tagName() }}</div>
                    <div class="inline text-gray-light text-xs ml-1">
                        {{ \App\Date::parse($upvote->created_at)->diffForHumans() }}
                    </div>
                </div>
            </div>
            <div class="text-center mb-8">
                <a class="naked-link block" href="/{{ $upvote->user->username }}">
                    <img class="w-8 h-8 rounded-full border-2 border-primary-lighter hover:border-primary"
                         src="{{ $upvote->user->avatar_path }}">
                </a>
                <a class="naked-link block" href="/{{ $upvote->user->username }}">
                    {{ $upvote->user->getFirstName() }}
                </a>
            </div>
            <div class="container text-center text-4xl text-gray-light">
                <!--<input type="hidden" v-model="message">-->
                <!--<a class="naked-link mr-3" href="javascript://"-->
                <!--v-clipboard:copy="message"-->
                <!--v-clipboard:success="linkedinShare"-->
                <!--v-clipboard:error="onError"><i class="fab fa-linkedin"></i></a>-->
                <a class="naked-link" target="_blank" href="{{ $upvote->twitterShareUrl() }}">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </section>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">
        pageData = {
            upvote: { {!! \App\Util::jsonEncodeWithoutBrackets($upvote->toArray()) !!} },
            editing: false,
            message: null,
        };

        pageMounted = function (Vue) {
            window.addEventListener('keyup', Vue.hotkeys);

            if (`{{ app('request')->input('editing') ? 'true' : 'false' }}`) {
                this.editing = true;
                this.$nextTick(() => {
                    this.$refs.message.focus();
                });
            }
        };

        pageMethods = {
            hotkeys(e) {
                if (e.key === 'Escape') {
                    this.editing = false;
                }
            },
            markdown: function (content) {
                let converter = new showdown.Converter();
                return converter.makeHtml(content);
            },
            linkedinShare() {
                this.$toasted.show('Copied message to clipboard. Opening LinkedIn share window now.');
                setTimeout(() => {
                    let url = 'https://www.linkedin.com/shareArticle?mini=true&url=' + window.location.href;
                    window.open(url);
                }, 1000);
            },
            linkedinShareContent() {
                let hashtag = this.upvote.tag_slug ? this.upvote.tag_slug.replace('-', '') : null;
                return "I just gave @" + this.upvote.tagged_user_firstname + " some props:\r\n\r\n" +
                    '"' + this.shortenedMessage + '"' + "\r\n\r\n" +
                    window.location.href + "\r\n\r\n" +
                    '#' + hashtag;
            },
            editIfOwner() {
                if (!this.loggedInUser.id) {
                    return;
                }

                if (this.loggedInUser.id !== this.upvote.author_id) {
                    return;
                }

                this.editing = true;
                this.$nextTick(() => {
                    this.$refs.message.focus();
                });
            },
            cancel() {
                this.editing = false;
            },
            save() {
                this.editing = false;
                this.upvote.message = this.$refs.message.value;

                axios.post(this.api("upvotes/" + this.upvote.id), {
                    'message': this.upvote.message
                }).then((response) => {
                    this.$toasted.show('Saved your shout-out');
                    this.user = response.data;
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
            loggedInUser() {
                return this.$cookies.get('user');
            },
        };
    </script>
@stop