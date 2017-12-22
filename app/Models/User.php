<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
