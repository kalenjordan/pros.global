<?php
/** @var \App\SavedSearch $savedSearch */
?>

<hr class="mt-16 mb-16"/>
<div class="footer animated mx-auto max-w-2xl leading-tight sm:mt-16 pb-16">
    <div class="section footer--inner flex flex-wrap">
        <div class="footer--column flex-2 m-4" style="flex-basis: 15rem;">
            <div class="mb-2">
                <a href="/">
                    <img class="logo" src="/images/logo.png">
                </a>
            </div>
            <p class="mb-2">
                pros.global is a matchmaking platform for founders, eCommerce professionals,
                and software developers
                being built by
                <a href="/kalenjordan">Kalen</a>
                with &hearts; in
                <a href="/s/austin">Austin</a>
                .
            </p>
            <p class="mb-2">
                pros.global is currently open-source-ish. You can take a look at the
                <a href="https://github.com/kalenjordan/pros.global">readme</a> and get in touch if you're
                interested in contributing or using it.
            </p>
            <p class="mb-2">
                If you're interested in signing up to create a profile on the site, just go ahead and
                <a href="/auth/linkedin" target="_blank">login</a> - once logged in you can edit your profile page.
            </p>
            <p>
                Copyright 2019. All rights reserved.
            </p>
        </div>

        <div class="footer--column flex-1 m-4" style="flex-basis: 10rem;">
            <h3 class="mb-2">Skills</h3>
            <ul class="list-reset">
                @foreach ($footerSavedSearches->get() as $savedSearch)
                    @if ($savedSearch->icon != 'location_on')
                        <li class="pb-2">
                            <a class="naked-link" href="/s/{{ $savedSearch->getSlugOrId() }}">
                                {{ $savedSearch->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="footer--column flex-1 m-4" style="flex-basis: 10rem;">
            <h3 class="mb-2">Locations</h3>
            <ul class="list-reset">
                @foreach ($footerSavedSearches->get() as $savedSearch)
                    @if ($savedSearch->icon == 'location_on')
                        <li class="pb-2">
                            <a class="naked-link" href="/s/{{ $savedSearch->getSlugOrId() }}">
                                {{ $savedSearch->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="footer--column flex-1 m-4" style="flex-basis: 15rem;">
            <h3 class="mb-2">Follow us</h3>
            <p class="mb-1">
                Sign up to get email updates:
            </p>
            <div class="email-signup mb-4">
                <input class="text p-2" type="text" style="width: 178px;" placeholder="you@example.com">
                <a class="btn px-3 py-1 ml-1"><i class="material-icons align-middle">mail_outline</i></a>
            </div>
            <div class="text-2xl gray-lighter mb-4">
                <a class="naked-link mr-2" href="https://twitter.com/kalenjordan">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="naked-link mr-2" href="https://github.com/kalenjordan/pros.global">
                    <i class="fab fa-github"></i>
                </a>
                <a class="naked-link mr-2" href="https://www.linkedin.com/company/35561588/">
                    <i class="fab fa-facebook"></i>
                </a>
            </div>
            <div class="mb-4">
                <ul class="list-reset">
                    <li class="pb-2">
                        <router-link class="naked-link" :to="{ path: '/search'}">Search</router-link>
                    </li>
                    <li class="pb-2">
                        <a class="naked-link" href="javascript://" @click="showKeyboardShortcuts()">Keyboard shortcuts</a>
                    </li>
                </ul>
            </div>
            {{--<div v-if="this.loggedInUser && this.loggedInUser.is_admin">--}}
                {{--<div class="mb-2">Admin Tasks</div>--}}
                {{--<a v-if="user" class="mb-2 naked-link" href="javascript://" @click="mergeUser">Merge user</a>--}}
                {{--<a class="mb-2 naked-link" href="javascript://" @click="addUser">Add user</a>--}}
            {{--</div>--}}
        </div>
    </div>
</div>