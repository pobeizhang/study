<?php

namespace App\Models;

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
}
