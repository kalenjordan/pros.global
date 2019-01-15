<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Query\Builder;

/**
 * @package App
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder find($id)
 */
class TaggedUpvote extends Model
{
    protected $guarded = [];
    protected $table = 'tagging_tagged_upvotes';

    public function user()
    {
        $user = $this->belongsTo('App\User');
        return $user;
    }

    public function toArray()
    {
        // Nothing for now
        $data = parent::toArray();
        return $data;
    }

    public static function findByTaggedIdAndUserId($taggedId, $userId)
    {
        return self::where('tagged_id', $taggedId)
            ->where('user_id', $userId)
            ->first();
    }
}