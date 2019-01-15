<?php

namespace App;

use \Illuminate\Database\Query\Builder;

/**
 * Class Tag
 *
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Tagged find($id)
 */
class Tagged extends \Conner\Tagging\Model\Tagged
{

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

    public function toArray()
    {
        $data = parent::toArray();

        $data['name'] = $data['tag_name'];
        unset($data['tag_name']);

        $data['slug'] = $data['tag_slug'];
        unset($data['tag_slug']);

        $data['upvote_count'] = TaggedUpvote::where('tagged_id', $this->id)->count();

        $userId = 1; // TODO pull from auth
        $data['is_upvoted_by_me'] = TaggedUpvote::where('user_id', $userId)
            ->where('tagged_id', $this->id)
            ->count();

        return $data;
    }

    public function toggleUpvote()
    {
        $userId = 1; // TODO pull from auth
        $upvote = TaggedUpvote::findByTaggedIdAndUserId($this->id, $userId);
        if ($upvote) {
            $upvote->delete();
        } else {
            $upvote = TaggedUpvote::create([
                'tagged_id' => $this->id,
                'user_id'   => $userId,
            ]);
        }
    }
}
