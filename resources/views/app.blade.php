<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link href="/css/app.css" rel="stylesheet" type="text/css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-secondary-color-lightest">
    <div id="app" class="wrapper">
        <div class="header">
            <h1>
                Meet a co-founder, advisor, or team member&mdash;or just mingle with other
                like-minded founders
            </h1>
        </div>
        <div class="centered bg-secondary-color-lightest pb-2 pt-2">
            <!-- <h2 class="mb-1">Founders that are interested in advising:</h2> -->
            <card-group></card-group>
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
