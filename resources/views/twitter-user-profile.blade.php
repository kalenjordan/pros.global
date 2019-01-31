<?php
/** @var \App\User $user */
/** @var \App\Tagged $tagged */
?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="twitter-card twitter-card-user-profile relative bg-white p-8 mx-auto text-center flex items-center" >
        <img class="logo w-3rem absolute opacity-50" src="/img/icon-200.png">
        <div>
            <div class="avatar-wrapper mb-2">
                <img class="rounded-full border-4 border-secondary w-35rem" src="{{ $user->avatar_path }}">
            </div>
            <h1 class="font-15rem mb-2">
                {{ $user->headline }}
            </h1>
            <div class="tags font-80">
                @foreach ($user->tagged as $tagged)
                    <div class="tag">
                        <span class="tag-name">
                            @if ($tagged->icon)
                                <i class="{{ $tagged->icon }}"></i>
                            @endif
                            {{ $tagged->tag_name }}
                        </span>
                        @if ($tagged->upvote_count)
                            <span class="tag-count">{{ $tagged->upvote_count }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>