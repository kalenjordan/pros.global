<?php

namespace App;

use App\Notifications\Notification;
use DB;

use Carbon\Carbon;
use Conner\Tagging\Taggable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
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
 * @property $id
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
 * @property $updated_at
 *
 * @property $notifications
 * @property DatabaseNotificationCollection $unreadNotifications
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
        'added_by',
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

    /**
     * @param $string
     *
     * @return null|string|string[]
     * @throws \Exception
     */
    public static function generateUniqueUsername($string)
    {
        $username = preg_replace("/[^a-z0-9.]+/i", "", strtolower($string));
        if (!User::findByUsername($username)) {
            return $username;
        }

        $usernameOriginal = $username;
        for ($i = 1; $i <= 9; $i++) {
            $username = $usernameOriginal . $i;
            if (!User::findByUsername($username)) {
                return $username;
            }
        }

        throw new \Exception("Problem generating username");
    }

    public function toArray()
    {
        $data = parent::toArray();

        if (strpos($data['avatar_path'], 'http:') === false && strpos($data['avatar_path'], 'https:') === false) {
            $data['avatar_path'] = env('APP_URL') . $data['avatar_path'];
        }

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

    public function upvotes()
    {
        return $this->hasMany('App\TaggedUpvote', 'tagged_user_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'user_id');
    }

    public function lastOnlineAt()
    {
        return $this->last_online_at ? Carbon::parse($this->last_online_at) : null;
    }

    /**
     * @return DatabaseNotificationCollection
     */
    public function notificationsToEmail()
    {
        return Notification::toBeEmailed()
            ->where('notifiable_id', $this->id);
    }

    public function url()
    {
        return env('APP_URL') . '/' . $this->username;
    }

    public function downloadAndSave($imageUrl)
    {
        $fileName = 'user_' . $this->id . md5($imageUrl . time()) . '.jpg';

        $img = public_path("avatars/$fileName");
        file_put_contents($img, file_get_contents($imageUrl));

        return "/avatars/$fileName";
    }

    public function toSearchIndexArray()
    {
        $data = $this->toArray();
        unset($data['being_impersonated']);

        $data['tags'] = $this->tagged()->pluck('tag_name');
        $data['type'] = 'user';
        $data['url'] = '/' . $this->username; // Has to be relative for <router-link>
        $data['object_id'] = $this->searchIndexId();
        
        return $data;
    }

    public function searchIndexId()
    {
        return env('APP_ENV') . '_user_' . $this->id;
    }
}
