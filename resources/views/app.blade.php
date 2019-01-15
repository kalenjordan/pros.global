<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/preflight.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/utilities.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet" type="text/css">


    <script src="https://rawgit.com/showdownjs/showdown/develop/dist/showdown.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app" class="wrapper">
        <router-view></router-view>
        <hr/>
        <div class="footer mx-auto max-w-lg text-xs leading-tight">
            <div class="section footer--inner">
                <div class="footer--column flex-3 p-3">
                    <h3 class="mb-2">About</h3>
                    <p class="mb-2">
                        Founder Land is a matchmaking platform for founders
                        being built by
                        <router-link :to="{ name: 'profile', params: {username: 'kalenjordan'}}">Kalen</router-link>
                        with &hearts; in
                        <router-link :to="{ name: 'city', params: {slug: 'austin'}}">Austin</router-link>.
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
                        <input class="text" type="text" style="width: 173px;" placeholder="you@example.com">
                        <a class="btn"><i class="fa fa-envelope"></i></a>
                    </div>
                    <div class="text-2xl gray-lighter">
                        <a class="naked-link" href="https://twitter.com/kalenjordan"><i class="fab fa-twitter pr-05"></i></a>
                        <a class="naked-link" href="https://github.com/kalenjordan/founderland"><i class="fab fa-github pr-05"></i></a>
                        <a class="naked-link" href="https://github.com/kalenjordan/founderland"><i class="fab fa-linkedin pr-05"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
