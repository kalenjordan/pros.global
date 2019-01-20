<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('DEFAULT_TITLE') }}</title>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132790317-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-132790317-1');
    </script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">

    <script src="https://rawgit.com/showdownjs/showdown/develop/dist/showdown.min.js"></script>
    <script>
        window.user={!! Auth::user() ? Auth::user()->toJson() : "{}" !!};
        window.api_token = "{{Auth::user() ? Auth::user()->getOrCreateApiToken() : "" }}";
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app" class="wrapper">
        <router-view :key="$route.fullPath"></router-view>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
