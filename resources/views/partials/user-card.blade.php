<?php
/** @var $user \App\User */
/** @var $tagged \App\Tagged */
?>

<div class="card hoverable user-card {{ isset($css) ? $css : "" }}">
    <div class="card--background bg-primary"></div>
    <div class="card--inner p-4">
        <div class="card--avatar">
            <a class="relative" href="/{{ $user->username }}">
                <img src="{{ $user->avatar_path }}">
            </a>
        </div>
        <div class="card--cta">
            <a class="btn px-5 py-2" href="/{{ $user->username }}">
                View
            </a>
        </div>
        <div class="card--identity mb-2">
            <div class="card--identity--name">
                <a class="naked-link" href="/{{ $user->username }}">
                    {{ $user->name }}
                </a>
            </div>
            <div class="card--identity--handle font-small">
                <a class="naked-link" href="/{{ $user->username }}">
                    {{ '@' . $user->username }}
                </a>
            </div>
        </div>
        <div class="card--about text-xs mb-2 leading-tight" style="-webkit-box-orient: vertical">
            {{ $user->headline }}
        </div>
        <div class="card--tags text-xs">
            @foreach ($user->tagged as $tagged)
                <a href="/tag/{{ $tagged->tag_slug }}">
                    <div class="tag mini-tag animated fast">
                        <span class="tag-name">
                            @if ($tagged->icon)
                                <i class="tag-icon material-icons">{{ $tagged->icon }}</i>
                            @endif
                            {{ $tagged->tag_name }}
                        </span>
                        @if ($tagged->upvote_count)
                            <span class="separator">&nbsp;</span>
                            <span class="count-and-upvote">
                                <span class="tag-count">{{ $tagged->upvote_count }}</span>
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>