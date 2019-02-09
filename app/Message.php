<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * @package App
 * @method static Message create($params)
 *
 * @property User $toUser
 * @property User $author
 * @property $message
 * @property $user_id
 *
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Message extends Model
{

    protected $fillable = [
        'message',
        'user_id',
        'to_user_id',
    ];

    public function toUser()
    {
        return $this->belongsTo('App\User', 'to_user_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
