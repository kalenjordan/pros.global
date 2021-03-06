<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Query\Builder;

/**
 * @package App
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder from()
 * @method static Builder find($id)
 *
 * @property Tagged $tagged
 * @property User   $user
 * @property User   $tagged_user
 * @property        $message
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

    public function tagName()
    {
        return $this->tagged ? $this->tagged->tag_name : "Deleted Tag";
    }

    public function getMessage()
    {
        return isset($this->message) ? $this->message : (
            $this->user->getFirstName()
            . ' upvoted '
            . $this->tagged_user->getFirstName()
            . ' for '
            . $this->tagName()
        );
    }

    public function toArray()
    {
        // Nothing for now
        $data = parent::toArray();
        $authorData = $this->user->toArray();
        $taggedUserData = $this->tagged_user->toArray();

        $data['author_id'] = $this->user->id;
        $data['author_firstname'] = $this->user->getFirstName();
        $data['author_username'] = $this->user->username;
        $data['author_avatar'] = $authorData['avatar_path'];
        $data['tagged_user_firstname'] = $this->tagged_user->getFirstName();
        $data['tagged_user_avatar'] = $taggedUserData['avatar_path'];
        $data['tagged_username'] = $this->tagged_user->username;
        $data['tagged_twitter_username'] = $this->tagged_user->twitter_username;
        $data['tag_name'] = $this->tagName();
        $data['tag_slug'] = $this->tagged ? $this->tagged->tag_slug : "deleted";
        $data['message'] = $this->getMessage();

        return $data;
    }

    public static function findByTaggedIdAndUserId($taggedId, $userId)
    {
        return self::where('tagged_id', $taggedId)
            ->where('user_id', $userId)
            ->first();
    }

    public function twitterShareUrl()
    {
        $hashtag = $this->tag_slug ? str_replace($this->tag_slug, '-', '') : null;
        $url = env('APP_URL') . '/upvotes/' . $this->id;

        $name = $this->tagged_user->twitter_username ? ('@' . $this->tagged_user->twitter_username) :
            $this->tagged_user->name;

        $text = "I just gave " . $name . " some props:\r\n\r\n" .
        '"' . $this->shortenedMessage() . '"' . "\r\n\r\n" .
        $url . "\r\n\r\n" .
        '#' . $hashtag;

        return 'https://twitter.com/intent/tweet?text=' . urlencode($text);
    }

    public function shortenedMessage()
    {
        $n = 180;
        if (! $this->message) {
            return null;
        }

        if (strlen($this->message) < $n) {
            return $this->message;
        }

        return substr($this->message, 0, $n) . '...';
    }
}