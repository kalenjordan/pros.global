<?php
/** @var \App\User $user */
/** @var \App\Tagged $tagged */
?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
</head>
<body class="flex items-center">
    <div class="twitter-card-bigger twitter-card-user-profile relative mx-auto text-left flex" >
        <div class="w-full">
            <div class="image-wrapper relative inline-block">
                <img class="avatar rounded-full" src="{{ $user->avatar_path }}">
                <img class="logo absolute" src="/img/icon-200.png">
            </div>
            <h1 class="font-15rem mb-4" style="-webkit-box-orient: vertical;">
                {{ $user->headline }}
            </h1>
            <div class="tags font-80" >
                @foreach ($user->tagged as $tagged)
                    <div class="tag">
                        <span class="tag-name">
                            @if ($tagged->icon)
                                <i class="tag-icon material-icons">{{ $tagged->icon }}</i>
                            @endif
                            {{ $tagged->tag_name }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>