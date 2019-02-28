<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property $id
 * @property $title
 * @property $slug
 * @property $body
 * @property $user_id
 *
 * @property User $user
 *
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Post create($params)
 * @method static Post find($id)
 */
class Post extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function findBySlugOrId($slugOrId)
    {
        $post = self::findBySlug($slugOrId);
        if (!$post) {
            $post = self::find($slugOrId);
        }

        return $post;
    }

    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    public function getSlugOrId()
    {
        return ($this->slug ? $this->slug : $this->id);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['url'] = $this->url();

        return $data;
    }

    public function url()
    {
        return $this->user->url() . "/posts/" . $this->getSlugOrId();
    }
}
