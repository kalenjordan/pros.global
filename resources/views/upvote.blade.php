<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Shout-out to {{ $upvote['tagged_user_firstname'] }} from {{ $upvote['author_firstname'] }} | pros.global
    </title>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kalenjordan">
    <meta name="twitter:creator" content="{{ "@" . $upvote['author_username'] }}">
    <meta name="twitter:title"
          content="Shout-out to {{ $upvote['tagged_user_firstname'] }}
                  from {{ $upvote['author_firstname'] }}"
    >
    <meta name="twitter:image"
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/upvotes/" . $upvote['id'] . "/twitter-card-html") }}"
          />

    <meta property='og:title' content='Shout-out to
        {{ $upvote['tagged_user_firstname'] }}
            from {{ $upvote['author_firstname'] }} | pros.global'/>
    <meta property='og:image'
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/upvotes/" . $upvote['id'] . "/twitter-card-html") }}"
    />
    <meta property='og:url' content='{{ env('APP_URL') }}/upvotes/{{ $upvote['id'] }}'/>

    @if (env('GOOGLE_ANALYTICS_ENABLED'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132790317-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-132790317-1');
        </script>
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">

    <script src="https://rawgit.com/showdownjs/showdown/develop/dist/showdown.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff"/>
    <link href="/img/splashscreens/iphone5_splash.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/iphone6_splash.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/iphoneplus_splash.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/iphonex_splash.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/iphonexr_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/iphonexsmax_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/ipad_splash.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/ipadpro1_splash.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/ipadpro3_splash.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <link href="/img/splashscreens/ipadpro2_splash.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image"/>
    <noscript>
        Javascript not enabled
    </noscript>
</head>
<body>
    <div id="app" class="wrapper">
        <router-view :key="$route.fullPath"></router-view>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
