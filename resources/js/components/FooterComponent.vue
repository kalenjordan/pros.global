<template>
    <div class="footer mx-auto max-w-lg text-xs leading-tight sm:mt-16 pb-16">
        <div class="section footer--inner flex flex-wrap">
            <div class="footer--column flex-3 p-3">
                <h3 class="mb-2">About</h3>
                <p class="mb-2">
                    Founder Land is a matchmaking platform for founders
                    being built by
                    <router-link :to="{ name: 'profile', params: {username: 'kalenjordan'}}">Kalen</router-link>
                    with &hearts; in
                    <router-link :to="{ name: 'tag', params: {slug: 'austin'}}">Austin</router-link>.
                </p>
                <p>
                    Copyright 2019. All rights reserved.
                </p>
            </div>

            <div class="footer--column flex-1 p-3">
                <h3 class="mb-2">Resources</h3>
                <ul>
                    <li><a class="naked-link" href="https://github.com/kalenjordan/founderland">Open source</a></li>
                    <li><a class="naked-link" href="/tags">Tags</a></li>
                    <li><a class="naked-link" href="/cities">Cities</a></li>
                </ul>
            </div>

            <div class="footer--column flex-2 p-3">
                <h3 class="mb-2">Follow us</h3>
                <p class="mb-1">
                    Sign up to get email updates:
                </p>
                <div class="email-signup mb-4">
                    <input class="text p-1" type="text" style="width: 173px;" placeholder="you@example.com">
                    <a class="btn px-3 py-1"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="text-2xl gray-lighter">
                    <a class="naked-link mr-2" href="https://twitter.com/kalenjordan"><i class="fab fa-twitter"></i></a>
                    <a class="naked-link mr-2" href="https://github.com/kalenjordan/founderland"><i class="fab fa-github"></i></a>
                    <a class="naked-link mr-2" href="https://github.com/kalenjordan/founderland"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
</template>