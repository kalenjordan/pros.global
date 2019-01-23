<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="upvote-twitter-card relative bg-white p-4 mx-auto text-center" style="width: 600px; height: 300px;" >
        <i class="fas fa-bolt absolute gray-lighter"></i>
        <div class="avatar-wrapper mb-2">
            <img class="rounded-full border-4 border-secondary" src="{{ $upvote['tagged_user_avatar'] }}">
        </div>
        <div class="message" style="-webkit-box-orient: vertical;">
            {!! Markdown::convertToHtml($upvote['message']) !!}
        </div>
        <div class="author-wrapper absolute flex items-center">
            <img class="rounded-full w-6 h-6 mr-2 border-2 border-primary" src="{{ $upvote['author_avatar'] }}">
            {{ $upvote['author_firstname'] }}
        </div>
    </div>
</body>
</html>