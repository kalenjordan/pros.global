<?php

namespace App\Listeners;

use Auth;

use App\Tagged;
use App\User;
use Conner\Tagging\Events\TagAdded;

class AssociateTaggingUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TagAdded  $event
     * @return void
     */
    public function handle(TagAdded $event)
    {
        /** @var User $taggedUser */
        $taggedUser = $event->model;

        /** @var Tagged $tagged */
        $tagged = $taggedUser->tagged->last();
        if (Auth::user()) {
            $tagged->tagged_by = Auth::user()->id;
            $tagged->save();
        }
    }
}