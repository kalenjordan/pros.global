@extends('app')

<?php
/** @var \App\SavedSearch $savedSearch */
?>

@section('title')
    <title>
        Shout-out to {{ $upvote['tagged_user_firstname'] }} from {{ $upvote['author_firstname'] }} | pros.global
    </title>
@endsection

@section('meta-twitter-card')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kalenjordan">
    <meta name="twitter:creator" content="{{ "@" . $upvote['author_username'] }}">
    <meta name="twitter:title"
          content="Shout-out to {{ $upvote['tagged_user_firstname'] }}
                  from {{ $upvote['author_firstname'] }}"
    >
    <meta name="twitter:image"
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/upvotes/" . $upvote['id'] . "/twitter-card") }}"
    />

    <meta property="og:type" content="website"/>
    <meta property='og:title' content='Shout-out to {{ $upvote['tagged_user_firstname'] }} from
        {{ $upvote['author_firstname'] }} | pros.global'/>
    <meta property='og:description' content='{{ substr($upvote['message'], 0, 100) . '...' }}'/>
    <meta property='og:image'
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/upvotes/" . $upvote['id'] . "/twitter-card") }}"
    />
    <meta property='og:url' content='{{ env('APP_URL') }}/upvotes/{{ $upvote['id'] }}'/>
@endsection