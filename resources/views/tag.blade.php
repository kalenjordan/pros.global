@extends('app')

<?php
/** @var \App\Tag $tag */
?>

@section('title')
    <title>
        Tag: {{ $tag->name }} | pros.global
    </title>
    <meta name="description"
          content="Pros that are tagged with {{ $tag->name }}">
@endsection

@section('server-side-rendered')
    <h1>{{ $tag->name }}</h1>

    <h3>Users: </h3>
    @foreach ($tag->users()->get() as $user)
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
@endsection

