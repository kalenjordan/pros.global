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
 */
class Tag extends \Conner\Tagging\Model\Tag
{
    public function toArray()
    {
        $data = parent::toArray();
        $data['users'] = User::withAllTags([$this->slug])->get();

        return $data;
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
