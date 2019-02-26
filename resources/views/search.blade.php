@extends('_layouts.base')

<?php

/** @var \App\User $user */

?>

@section('title')
    <title>Search{{ $query ? ": " . $query : "" }}</title>
@stop

@section('content')
    <div class="page page-search">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="mb-6 max-w-md mx-auto">
            <form action="/search" class="flex m-4 mt-6">
                <input name="q" placeholder="e.g. tag:founder"
                       class="text font-100 flex-5 no-border mr-2 p-2"
                       value="{{ $query }}"
                >
                <input type="submit" class="btn flex-1 px-5 py-2 text-center" style="flex-basis: 50px; flex-grow: inherit;" @click="search">
                <a class="btn px-5 py-2 ml-2" @click="saveSearch"><i class="fas fa-save"></i></a>
            </form>
        </section>
        <section class="max-w-md mx-auto" v-bind:class="{opacity50 : search_processing}">
            @if ($users->count())
                @foreach ($users->get() as $searchUser)
                    @include ('partials.search-result-card', ['user' => $searchUser, 'css' => 'hoverable mb-4'])
                @endforeach
            @endif
            <div v-else>
                <div class="p-4 text-center">
                    <div class="italic mb-4 text-gray-dark">
                        No pros were found. Want to add someone you know from Twitter?
                    </div>
                    <div>
                        <input class="p-4 mr-4"
                               placeholder="e.g. @username"
                               ref="twitterUsername"
                        >
                        <a class="btn px-5 py-3" @click="addTwitterUser">
                            <i class="fab fa-twitter mr-1"></i>
                            Add
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

@section('footer-script')
    <script type="text/javascript">
        pageMounted = function (Vue) {
            window.addEventListener('keyup', Vue.hotkeys);

            if ({{ app('request')->input('editing') ? 'true' : 'false' }}) {
                Vue.editing = true;
                Vue.$nextTick(() => {
                    Vue.$refs.message.focus();
                });
            }
        };

        pageMethods = {
            hotkeys(e) {
            },
            submit: function (content) {
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
                    this.upvote = response.data;
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