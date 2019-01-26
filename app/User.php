<?php

namespace App;

use Carbon\Carbon;
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
 * @property $is_admin
 * @property $last_online_at
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

        $parts = explode(' ', $this->name);
        $data['first_name'] = isset($parts[0]) ? $parts[0] : null;

        $manager = app('impersonate');
        $manager->findUserById(env('ADMIN_USER_ID'));
        $data['being_impersonated'] = $manager->isImpersonating();

        return $data;
    }

    public function toArrayForCookie()
    {
        $data = [
            'id'          => $this->id,
            'name'        => $this->name,
            'api_token'   => $this->getOrCreateApiToken(),
            'avatar_path' => $this->avatar_path,
            'username'    => $this->username,
            'is_admin'    => $this->is_admin,
        ];

        $manager = app('impersonate');
        $manager->findUserById(env('ADMIN_USER_ID'));
        $data['being_impersonated'] = $manager->isImpersonating();

        return $data;
    }

    public function tags()
    {
        return $this->hasMany('App\Tagged', 'taggable_id')
            ->leftJoin("tagging_tags", function($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("tagging_tags.slug", '=', 'tagging_tagged.tag_slug');
            })->select([
                'tagging_tagged.*',
                'tagging_tags.icon',
            ])
            ->orderBy('tagging_tags.icon', 'desc')
            ->orderBy('upvote_count', 'desc');
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

    public function lastOnlineAt()
    {
        return $this->last_online_at ? Carbon::parse($this->last_online_at) : null;
    }
}
