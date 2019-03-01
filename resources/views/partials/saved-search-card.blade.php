<?php
/** @var \App\SavedSearch $savedSearch */
/** @var \App\User $user */
/** @var \App\Tagged $tagged */

$users = $savedSearch->fetchUsers();

?>
<div class="card saved-search-card hoverable {{ isset($css) ? $css : "" }}">
    <div class="card--background bg-secondary">
        <h3 class="text-center">
            <a class="naked-link" href="/s/{{ $savedSearch->getSlugOrId() }}">
                {{ $savedSearch->name }}
            </a>
        </h3>
    </div>
    <div class="card--avatar">
        <div class="card--avatar--inner">
            <div class="icon-wrapper">
                @if ($savedSearch->icon)
                    <i class="material-icons">{{ $savedSearch->icon }}</i>
                @else
                    <i class="material-icons">search</i>
                @endif
            </div>
        </div>
    </div>
    <div class="card--inner p-2">
        @if ($users && $users->count())
            <div class="saved-search--users flex flex-wrap">
                @foreach ($users->limit(6)->get() as $user)
                    <div class="saved-search--user flex-1">
                        <a class="no-link" href="/{{ $user->username }}">
                            <div class="mini-card m-2 p-3 border border-gray-lighter hover:border-gray-light text-center">
                                <div class="user-avatar inline-block relative">
                                    <img class="w-12 h-12 rounded-full border-2 border-secondary-light"
                                         src="{{ $user->avatar_path }}">
                                </div>
                                <div>
                                    <div class="user-name text-sm mb-1" style="-webkit-box-orient: vertical">
                                        {{ $user->name }}
                                    </div>
                                    <div class="headline text-xs" style="-webkit-box-orient: vertical">
                                        {{ $user->headline }}
                                        <br/>
                                        @foreach ($user->tagged as $tagged)
                                            <span>{{ $tagged->tag_name }}</span>
                                            @if ($loop->remaining), @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>