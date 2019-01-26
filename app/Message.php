<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * @package App
 * @method static Message create($params)
 */
class Message extends Model
{
    protected $fillable = [
        'message',
        'user_id',
        'to_user_id',
    ];
}
