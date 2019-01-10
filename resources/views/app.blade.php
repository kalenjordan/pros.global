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
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="card--background"></div>
            <div class="card--inner">
                <div class="card--avatar">
                    <a href="#">
                        <img src="https://pbs.twimg.com/profile_images/959539398210547712/U5lQBX2N_400x400.jpg">
                    </a>
                </div>
                <div class="card--cta">
                    <a class="btn" href="#"><i class="far fa-thumbs-up"></i> Give Props</a>
                </div>
                <div class="card--identity mb-1">
                    <div class="card--identity--name bold">
                        <a href="#" class="naked-link">Kalen Jordan</a>
                    </div>
                    <div class="card--identity--handle font-small">
                        <a href="#" class="naked-link">@kalenjordan</a>
                    </div>
                </div>
                <div class="card--about font-small mb-1">
                    Magento fanboy. Small-time entrepreneur. Wannabe Youtuber. Not a recruiter. Founder @commercehero. Advisor @getmagemail. Co-host @magetalk
                </div>
                <div class="card--tags font-small">
                    <a class="tag" href="#"><i class="fa fa-location-arrow"></i> Austin</a>
                    <a class="tag" href="#">Magento</a>
                    <a class="tag" href="#">SaaS</a>
                    <a class="tag" href="#">Seeking Advisees</a>
                    <a class="tag" href="#">Developer</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
