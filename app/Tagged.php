<?php

namespace App;

use Auth;

/**
 * Class Tag
 *
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Tagged find($id)
 *
 * @property $taggable_id
 * @property $tag_name
 * @property $tag_slug
 * @property $upvote_count
 * @property $tagged_by
 */
class Tagged extends \Conner\Tagging\Model\Tagged
{

    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * @return Tagged
     */
    public static function findByUserIdAndSlug($userId, $slug)
    {
        /** @var Tagged $tagged */
        $tagged = self::where('taggable_id', $userId)
            ->where('tag_slug', $slug)
            ->first();

        return $tagged;
    }

    public function taggedUser()
    {
        return User::find($this->taggable_id);
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['name'] = $data['tag_name'];
        unset($data['tag_name']);

        $data['slug'] = $data['tag_slug'];
        unset($data['tag_slug']);

        $data['upvote_count'] = TaggedUpvote::where('tagged_id', $this->id)->count();
        $data['tagged_user_firstname'] = $this->taggedUser()->getFirstName();

        if (Auth::user()) {
            $userId = Auth::user()->id;
            $data['is_upvoted_by_me'] = TaggedUpvote::where('user_id', $userId)
                ->where('tagged_id', $this->id)
                ->count();
        }

        return $data;
    }

    public function toggleUpvote()
    {
        $userId = Auth::user()->id;
        $upvote = TaggedUpvote::findByTaggedIdAndUserId($this->id, $userId);

        if ($upvote) {
            $upvote->delete();
            $upvote->is_deleted = true;
            $this->upvote_count--;
        } else {
            $upvote = TaggedUpvote::create([
                'tagged_id'      => $this->id,
                'user_id'        => $userId,
                'tagged_user_id' => $this->taggable_id,
            ]);
            $this->upvote_count++;
        }

        $this->save();

        return $upvote;
    }
}
