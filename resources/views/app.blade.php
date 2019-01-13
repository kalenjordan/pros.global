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

    <script src="https://rawgit.com/showdownjs/showdown/develop/dist/showdown.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app" class="wrapper">
        <router-view></router-view>
        <hr/>
        <div class="footer">
            <div class="section footer--inner margin-auto">
                <div class="footer--column first">
                    <h3>Founder Land</h3>
                    <ul>
                        <li>Copyright 2019. All rights reserved.</li>
                        <li>Made with &hearts; in Austin</li>
                    </ul>
                </div>

                <div class="footer--column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a class="naked-link" href="https://kalenjordan.com">Blog</a></li>
                        <li><a class="naked-link" href="https://github.com/kalenjordan/founderland">We are open source!</a></li>
                    </ul>
                </div>

                <div class="footer--column">
                    <h3>Follow us</h3>
                    <a class="naked-link" href="https://twitter.com/kalenjordan"><i class="fab fa-twitter pr-05"></i></a>
                    <a class="naked-link" href="https://github.com/kalenjordan/founderland"><i class="fab fa-github pr-05"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
