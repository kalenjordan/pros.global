<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder withAllTags($array)
 * @method static SavedSearch find($id)
 * @method static SavedSearch create($params)
 *
 * @property      $name
 * @property      $query
 * @property User $user
 */
class SavedSearch extends Authenticatable
{

    protected $fillable = [
        'user_id',
        'name',
        'query',
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['users'] = $this->fetchUsers();

        return $data;
    }

    public function fetchUsers()
    {
        $users = User::where('id', '>', 0);
        if ($this->query) {
            $search = new UserSearch($users);
            $search->query($this->query);
        }

        return $users->get();
    }
}
