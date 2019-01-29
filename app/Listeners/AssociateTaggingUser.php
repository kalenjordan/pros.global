<?php

namespace App\Listeners;

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
        /** @var User $user */
        $user = $event->model;

        /** @var Tagged $tagged */
        $tagged = $user->tagged->last();
        $tagged->tagged_by = $user->id;
        $tagged->save();
    }
}