@extends('_layouts.base')

<?php
/** @var $home \App\SavedSearch */
?>

@section('title')
    <title>Home</title>
@stop

@section('meta-twitter-card')
    @include('partials.meta-twitter-card', [
        'title' => 'Home',
        'description' => 'Description',
        'image' => env('APP_URL') . "/home-twitter-card",
        'version' => 'v1',
    ])
@stop

@section('content')
    <div class="page-home">
        <top-nav class="m-4 sm:m-8"></top-nav>
        <section class="header text-center max-w-lg mx-auto mb-4">
            <h1 id="typewriter" class="mx-4 text-2xl sm:text-4xl">
                {{ $home->description }}
            </h1>
        </section>
        <section class="max-w-2xl mb-8 mx-auto">
            <div class="user-cards m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                @foreach ($home->fetchUsers()->limit(6)->get() as $homeUser)
                    @include ('partials.user-card', ['user' => $homeUser, 'css' => 'w-full sm:max-w-xs m-2'])
                @endforeach
            </div>
            <div class="centered">
                <a href="/search?q={{ $home->query }}" class="btn px-5 py-2">
                    See more
                    <i class="material-icons align-middle" style="margin-right: -7px;">keyboard_arrow_right</i>
                </a>
            </div>
        </section>

        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mx-auto">
            <div class="saved-searches m-2 mb-4 sm:mb-8 flex flex-wrap justify-center">
                @foreach ($home->relatedSavedSearches()->get() as $savedSearch)
                    @include ('partials.saved-search', ['savedSearch' => $savedSearch, 'css' => 'mb-12 m-4'])
                @endforeach
            </div>
        </section>

        <hr class="mt-16 mb-16"/>
        <section class="max-w-3xl mb-8 mt-8 mx-auto">
            <h2 class="text-center mx-auto mb-8">About</h2>
            <div class="font-120 mx-auto px-4" style="max-width: 40rem;">
                <p class="mb-4">
                    This platform is to facilitate various types of matchmaking. Having run a
                    <a href="https://commercehero.io">matchmaking platform</a> in a specific eCommerce developer niche successfully
                    for the last 2 years, I'm taking some of the learnings from that, and trying to create an offering that's a little bit more broad and flexible.
                </p>
                <p class="mb-4">
                    I'm not exactly sure what audiences will be best served by this, but currently the platform is being seeded with people in the communities that I'm most connected to—eCommerce professionals, developers, and bootstrapped software founders.
                </p>
                <p class="mb-4">
                    As the number of people on the platform grows, the value that we can provide in matchmaking for the purposes of hiring employees, contractors, or even finding a co-founder, advisor, or investor should improve.
                </p>
                <p class="mb-4">
                    If you have any questions, I'd love to
                    <a href="https://twitter.com/kalenjordan">hear from you</a>!
                </p>
            </div>
        </section>
    </div>
@stop

@section('footer-script')
@stop