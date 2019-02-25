<?php
/** @var \App\SavedSearch $savedSearch */
$users = $savedSearch->fetchUsers()->get();
?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="twitter-card-bigger twitter-card-saved-search relative p-0 mx-auto text-center flex items-center">
        <div class="logo-wrapper absolute w-full">
            <img class="logo mx-auto" src="/img/icon-200.png">
        </div>
        <div class="w-full">
            <div class="images mx-auto w-full ">
                <?php $count = 0; ?>
                @for ($i = 0; $i < count($users) && $i < 21; $i++)
                    <?php $user = $users[$i]; ?>
                    <img class="avatar inline-block rounded-full border-4 border-white" src="{{ $user->avatar_path }}">
                @endfor
            </div>
        </div>
    </div>
</body>
</html>