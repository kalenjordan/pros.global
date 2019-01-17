<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Query\Builder;

/**
 * @package App
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder from()
 * @method static Builder find($id)
 *
 * @property Tagged $tagged
 * @property User $user
 * @property User $tagged_user
 * @property $message
 */
class TaggedUpvote extends Model
{
    protected $guarded = [];
    protected $table = 'tagging_tagged_upvotes';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tagged_user()
    {
        return $this->belongsTo('App\User', 'tagged_user_id');
    }

    public function tagged()
    {
        return $this->belongsTo('App\Tagged');
    }

    public function toArray()
    {
        // Nothing for now
        $data = parent::toArray();
        $data['author_firstname'] = $this->user->getFirstName();
        $data['author_username'] = $this->user->username;
        $data['author_avatar'] = $this->user->avatar_path;
        $data['tag_name'] = $this->tagged->tag_name;
        $data['tagged_user_firstname'] = $this->tagged_user->getFirstName();

        return $data;
    }

    public static function findByTaggedIdAndUserId($taggedId, $userId)
    {
        return self::where('tagged_id', $taggedId)
            ->where('user_id', $userId)
            ->first();
    }
}