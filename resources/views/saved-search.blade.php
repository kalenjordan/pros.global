@extends('app')

<?php
/** @var \App\SavedSearch $savedSearch */
/** @var \App\User $user */
/** @var \App\Tagged $tagged */
?>

@section('title')
    <title>{{ $savedSearch->name }} | pros.global</title>
    <meta name="description"
          content="{{ $savedSearch->description ? $savedSearch->description : $savedSearch->name }}">
@endsection

@section('meta-twitter-card')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kalenjordan">
    <meta name="twitter:creator" content="@kalenjordan">
    <meta name="twitter:title" content="{{ $savedSearch->name }}">
    <meta name="twitter:image"
          content="https://image.thum.io/get/viewportWidth/900/viewportHeight/450/width/900/noanimate/?url={{ urlencode(env('APP_URL') . "/s/" . $savedSearch->getSlugOrId() . "/twitter-card") }}?v2"
    />

    <meta property='og:title' content='{{ $savedSearch->name }} | pros.global'/>
    <meta property='og:image'
          content="https://image.thum.io/get/viewportWidth/900/viewportHeight/450/width/900/noanimate/?url={{ urlencode(env('APP_URL') . "/s/" . $savedSearch->getSlugOrId() . "/twitter-card") }}?v2"
    />
    <meta property='og:url' content='{{ env('APP_URL') }}/s/{{ $savedSearch->getSlugOrId() }}'/>
@endsection

@section('server-side-rendered')
    <h1>{{ $savedSearch->name }}</h1>
    <h2>{{ $savedSearch->description }}</h2>

    <h3>Users: </h3>
    @foreach ($savedSearch->fetchUsers() as $user)
        <div>
            <h4>
                <a href="/{{ $user->username }}">{{ $user->name }}</a>
            </h4>
            <h5>{{ $user->headline }}</h5>
            <ul>
                @foreach ($user->tagged as $tagged)
                    <li><a href="/tag/{{ $tagged->tag_slug }}">{{ $tagged->tag_name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <h3>Related: </h3>
    @foreach ($savedSearch->relatedSavedSearches()->get() as $relatedSavedSearch)
        <h3>{{ $relatedSavedSearch->name }}</h3>
        @foreach ($relatedSavedSearch->fetchUsers() as $user)
            <div>
                <h4>
                    <a href="/{{ $user->username }}">{{ $user->name }}</a>
                </h4>
                <h5>{{ $user->headline }}</h5>
                <ul>
                    @foreach ($user->tagged as $tagged)
                        <li><a href="/tag/{{ $tagged->tag_slug }}">{{ $tagged->tag_name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endforeach
@endsection
