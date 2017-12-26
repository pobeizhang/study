<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * 用户模型
 *
 * Class User
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    //引用消息通知的trait
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 此方法会在模型类初始化完成之后进行加载
     */
    public static function boot ()
    {
        parent::boot();

        static::creating( function ( $user ) {
            $user->activation_token = str_random( 30 );
        } );
    }

    /**
     * 用户默认头像
     *
     * @param string $size
     *
     * @return string
     */
    public function gravatar ( $size = '100' )
    {
        $hash = md5( strtolower( trim( $this->attributes[ 'email' ] ) ) );
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * 发送重置密码邮件
     *
     * @param string $token
     */
    public function sendPasswordResetNotification ( $token )
    {
        $this->notify( new ResetPassword( $token ) );
    }

    /**
     * 模型关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses ()
    {
        return $this->hasMany( Status::class );
    }

    /**
     * 获取当前用户及其关注的用户发表过的所有微博
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feed ()
    {
        $user_ids = Auth::user()->followings()->pluck( 'users.id' )->toArray();
        array_push( $user_ids, Auth::user()->id );

        return Status::whereIn( 'user_id', $user_ids )
            ->with( 'user' )
            ->orderBy( 'created_at', 'desc' );
    }

    /**
     * 粉丝
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers ()
    {
        return $this->belongsToMany( User::Class, 'followers', 'user_id', 'follower_id' );
    }

    /**
     * 关注者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings ()
    {
        return $this->belongsToMany( User::Class, 'followers', 'follower_id', 'user_id' );
    }

    /**
     * 关注动作
     *
     * @param $user_ids
     *
     * @return array
     */
    public function follow ( $user_ids )
    {
        if ( !is_array( $user_ids ) ) {
            $user_ids = compact( 'user_ids' );
        }

        return $this->followings()->sync( $user_ids, false );
    }

    /**
     * 取消关注动作
     *
     * @param $user_ids
     *
     * @return int
     */
    public function unfollow ( $user_ids )
    {
        if ( !is_array( $user_ids ) ) {
            $user_ids = compact( 'user_ids' );
        }

        return $this->followings()->detach( $user_ids );
    }

    /**
     * 是否关注
     *
     * @param $user_id
     *
     * @return mixed
     */
    public function isFollowing ( $user_id )
    {
        return $this->followings->contains( $user_id );
    }
}
