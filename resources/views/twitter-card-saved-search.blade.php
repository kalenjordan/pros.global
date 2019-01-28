<?php
/** @var \App\SavedSearch $savedSearch */
?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="flex items-center">
    <div class="twitter-card twitter-card-saved-search relative bg-white p-8 mx-auto text-center"
         style="width: 600px; height: 300px;">
        <i class="fas fa-bolt absolute gray-lighter"></i>
        <div class="flex flex-wrap items-center h-full">
            <h1 class=" text-2rem -mb-16 w-full">{{ $savedSearch->name }}</h1>
            <div class="images mx-auto -ml-8 w-full ">
                <?php $count = 0; ?>
                @foreach ($savedSearch->fetchUsers() as $user)
                    <?php $count++; if ($count == 9) break; ?>
                    <img class="inline-block rounded-full w-5rem -mr-8 border-2 border-primary" src="{{ $user->avatar_path }}">
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>