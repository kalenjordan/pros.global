<?php

namespace App\Notifications;

use DB;
use App\Tagged;
use App\TaggedUpvote;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends \Illuminate\Notifications\Notification
{
    use Queueable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function toBeEmailed()
    {
        return DatabaseNotification::whereIn('data->from_user_id', self::whitelistedUserIds())
            ->whereNull('read_at')
            ->whereNull('emailed_at');
    }

    public static function whitelistedUserIds()
    {
        $adminUserIds = Tagged::where('tag_slug', 'admin')
            ->leftJoin("tagging_tagged_upvotes", function($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("tagging_tagged_upvotes.tagged_id", '=', 'tagging_tagged.id');
            })->select([
                'tagging_tagged.*',
            ])->where('tagging_tagged_upvotes.user_id', env('ADMIN_USER_ID'))
            ->get()->pluck('taggable_id');

        $whitelistedUserIds = TaggedUpvote::where('id', '>', 0)->whereIn('user_id', $adminUserIds)
            ->select([
                DB::raw('DISTINCT tagged_user_id')
            ])->get()->pluck('tagged_user_id');

        return $whitelistedUserIds;
    }
}
