<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="twitter-card-bigger twitter-card-upvote relative bg-white mx-auto text-left" >
        <img class="logo absolute" src="/img/icon-200.png">
        <img class="avatar rounded-full border-4 border-secondary" src="{{ $upvote['tagged_user_avatar'] }}">
        <div class="message" style="-webkit-box-orient: vertical;">
            {!! Markdown::convertToHtml($upvote['message']) !!}
        </div>
        <div class="author-wrapper absolute flex items-center">
            <img class="rounded-full mr-4 border-2 border-primary" src="{{ $upvote['author_avatar'] }}">
            {{ $upvote['author_firstname'] }}
        </div>
    </div>
</body>
</html>