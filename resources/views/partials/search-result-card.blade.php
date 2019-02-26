<?php

/** @var \App\User $user */
/** @var \App\Tagged $tagged */

?>

<div class="card user-card {{ isset($css) ? $css : '' }}">
    <div class="card--inner flex items-center p-4 flex-wrap">
        <div class="card--avatar flex-2 text-center -ml-1">
            <a class="relative" href="/{{ $user->username }}">
                <img src="{{ $user->avatar_path }}">
            </a>
        </div>
        <div class="flex-3 card--about text-sm sm:ml-2 leading-tight" style="-webkit-box-orient: vertical;">
            <div class="card--identity--name bold">
                <a href="/{{ $user->username }}" class="naked-link">
                    {{ $user->name }}
                </a>
            </div>
            <div>
                {{ $user->headline }}
            </div>
        </div>
        @if ($user->tagged)
            <div class="flex-4  card--tags text-xs" style="-webkit-box-orient: vertical">
                @foreach ($user->tagged as $tagged)
                    <a href="/tag/{{ $tagged->tag_slug }}">
                        <div class="tag mini-tag animated fast">
                            <div class="tag-name inline">
                                @if ($tagged->icon)
                                    <i class="tag-icon material-icons">{{ $tagged->icon }}</i>
                                @endif
                                {{ $tagged->tag_name }}
                            </div>
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
        @endif
    </div>
</div>