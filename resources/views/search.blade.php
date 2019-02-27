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
                <input type="submit" class="btn flex-1 px-5 py-2 text-center" style="flex-basis: 50px; flex-grow: inherit;">
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
        pageData = {
            users: [],
            search_processing: false,
            query: null,
        };

        pageMounted = function (Vue) {
            window.addEventListener('keyup', this.hotkeys);
            this.$refs.search.focus();
        };

        pageMethods = {
            search() {
                this.search_processing = true;
                window.history.replaceState({}, null, '/search?q=' + this.query);

                axios.get(this.api('users?q=' + this.query)).then((response) => {
                    this.search_processing = false;
                    this.users = response.data;
                });
            },
            saveSearch() {
                let name = prompt("Name for saved search");
                if (!name) {
                    return;
                }

                axios.post(this.api('/api/v1/saved-searches'), {
                    'name': name,
                    'query': this.$refs.search.value,
                }).then((response) => {
                    this.$toasted.show("" +
                        "Saved search: <a class='paragraph-link' href='/s/" + response.data.id + "'>" + name + "</a>" +
                        "");
                });
            },
            hotkeys(e) {
                if (document.activeElement.tagName === 'INPUT') {
                    if (e.key === 'Enter') {
                        this.search();
                    } else if (e.key === 'Escape') {
                        this.$refs.search.blur();
                    }
                } else if (document.activeElement.tagName === 'BODY') {
                    if (e.key === '/') {
                        this.$refs.search.focus();
                    }
                }
            },
            addTwitterUser() {
                if (!this.loggedIn) {
                    return alert('Please login first');
                }

                axios.get(this.api('twitter/add-user/' + this.$refs.twitterUsername.value)).then((response) => {
                    if (response.data.username) {
                        this.$router.push({
                            path: '/' + response.data.username,
                        });
                    } else if (response.data.message) {
                        alert(response.data.message);
                    }
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
            loggedInUser() {
                return this.$store.state.user;
            },
        };
    </script>
@stop