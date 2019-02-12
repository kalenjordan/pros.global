@extends('app')

<?php
/** @var \App\User $user */
/** @var \App\TaggedUpvote $upvote */
?>

@section('title')
    <title>{{ $user->name }} | pros.global</title>
    <meta name="description" content="{{ $user->headline }}">
@endsection

@section('meta-twitter-card')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kalenjordan">
    <meta name="twitter:creator" content="@kalenjordan">
    <meta name="twitter:title" content="{{ $user->name }}">
    <meta name="twitter:image" content="{{ env('IMAGE_API') }}?url={{ urlencode(env('APP_URL') . "/" . $user->username . "/twitter-card") }}" />

    <meta property='og:title' content='{{ $user->name }} | pros.global'/>
    <meta property='og:image' content="{{ env('IMAGE_API') }}?url={{ urlencode(env('APP_URL') . "/" . $user->username . "/twitter-card") }}" />
    <meta property='og:url' content='{{ env('APP_URL') }}/{{ $user->username }}'/>
@endsection

@section('server-side-rendered')
    <h1>{{ $user->name }}</h1>
    <h2>{{ $user->headline }}</h2>
    <ul>
        @foreach ($user->tagged as $tagged)
            <li><a href="/tag/{{ $tagged->tag_slug }}">{{ $tagged->tag_name }}</a></li>
        @endforeach
    </ul>
    @markdown($user->about)

    @foreach ($user->upvotes as $upvote)
        <div>
            @markdown($upvote->message())
            <p>--
                <a href="/{{ $upvote->user->username }}">{{ $upvote->user->name }}</a>
            </p>
        </div>
    @endforeach
@endsection
