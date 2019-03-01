<?php

/**
 * @var \App\User $author
 * @var \App\Post $post
 */
?>


<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="twitter-card-bigger twitter-card-post relative mx-auto text-left" >
        <div class="w-full" >
            <div align="right" class="image-wrapper relative inline-block">
                <img class="avatar rounded-full" src="{{ $author->avatar_path }}">
                <img class="logo absolute" src="/img/icon-200.png">
            </div>
            <p class="body" style="-webkit-box-orient: vertical;">
                {{ $post->title() }}
            </p>
        </div>
    </div>
</body>
</html>