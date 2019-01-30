@extends('app')

<?php
/** @var \App\SavedSearch $savedSearch */
?>

@section('title')
    <title>
        {{ $savedSearch->name }} | pros.global
    </title>
@endsection

@section('meta-twitter-card')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kalenjordan">
    <meta name="twitter:creator" content="@kalenjordan">
    <meta name="twitter:title" content="{{ $savedSearch->name }}">
    <meta name="twitter:image"
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/s/" . $savedSearch->getSlugOrId() . "/twitter-card") }}"
    />

    <meta property='og:title' content='{{ $savedSearch->name }} | pros.global'/>
    <meta property='og:image'
          content="https://image.thum.io/get/viewportWidth/600/viewportHeight/300/width/600/noanimate/?url={{ urlencode(env('APP_URL') . "/s/" . $savedSearch->getSlugOrId() . "/twitter-card") }}"
    />
    <meta property='og:url' content='{{ env('APP_URL') }}/s/{{ $savedSearch->getSlugOrId() }}'/>
@endsection