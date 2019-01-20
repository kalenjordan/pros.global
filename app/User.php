<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder withAllTags($array)
 * @method static User find($id)
 * @method static User create($params)
 *
 * @property $name
 * @property $email
 * @property $username
 * @property $about
 * @property $headline
 * @property $linkedin_url
 * @property $linkedin_token
 * @property $avatar_path
 * @property $upvotes
 * @property $tags
 * @property $tagged
 * @property $password
 * @property $api_token
 */
class User extends Authenticatable
{
    use Notifiable;
    use Taggable;
    use \Lab404\Impersonate\Models\Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar_path',
        'headline',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
        'linkedin_token',
        'api_token',
    ];

    public function getFirstName()
    {
        $parts = explode(' ', $this->name);
        if (!isset($parts[0])) {
            return null;
        }

        return $parts[0];
    }

    public function getOrCreateApiToken()
    {
        if ($this->api_token) {
            return $this->api_token;
        }

        $this->api_token = md5(env('APP_KEY') . time());
        $this->save();

        return $this->api_token;
    }

    public static function findByUsername($username)
    {
        return self::with('tagged')->where('username', $username)->first();
    }

    public static function findByEmail($username)
    {
        return self::where('email', $username)->first();
    }

    public static function findStubbedUserByName($name)
    {
        return self::where('email', 'like', '%@example.com')
            ->where('name', $name)
            ->first();
    }

    public static function findByName($name)
    {
        return self::where('name', $name)
            ->first();
    }

    public static function findByApiToken($username)
    {
        return self::where('api_token', $username)->first();
    }

    public function toArray()
    {
        $data = parent::toArray();

        $manager = app('impersonate');
        $manager->findUserById(env('ADMIN_USER_ID'));
        $data['being_impersonated'] = $manager->isImpersonating();

        return $data;
    }

    public function tags()
    {
        return $this->hasMany('App\Tagged', 'taggable_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @todo replace tags relation with tagged
     */
    public function tagged()
    {
        return $this->hasMany('App\Tagged', 'taggable_id');
    }

    public function upvotes()
    {
        return $this->hasMany('App\TaggedUpvote', 'tagged_user_id');
    }
}
