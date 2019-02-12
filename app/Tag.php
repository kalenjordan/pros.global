<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Notifications\Notifiable;

/**
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 *
 * @property $name
 * @property $slug
 * @property $headline
 * @property $icon
 * @property $upvote_count
 * @property $updated_at
 */
class Tag extends \Conner\Tagging\Model\Tag
{
    public function toArray()
    {
        $data = parent::toArray();
        $data['users'] = $this->users()->get()->toArray();

        return $data;
    }

    public function users()
    {
        $taggedIds = Tagged::where('tagging_tagged.id', '>', 0)
            ->where('tag_slug', $this->slug)
            ->get()->pluck('taggable_id');

        $users = User::whereIn('users.id', $taggedIds);

        return $users;
    }

    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * This overrides the capitalization formatting that's standard
     *
     * @see Taggable::addTag()
     */
    public static function displayer($string)
    {
        return $string;
    }
}
