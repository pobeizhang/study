<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * 用户相关授权策略
 *
 * Class UserPolicy
 *
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * UserPolicy constructor.
     */
    public function __construct ()
    {
        //
    }

    /**
     * 用户更新时的验证
     *
     * @param \App\Models\User $currentUser 当前登录用户实例
     * @param \App\Models\User $user        要进行授权的用户实例
     *
     * @return bool
     */
    public function update ( User $currentUser, User $user )
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 删除用户策略 | 只有管理员并且删除的不是自己时，才能执行删除操作
     *
     * @param \App\Models\User $currentUser 当前登录用户实例
     * @param \App\Models\User $user        要进行授权的用户实例
     *
     * @return bool
     */
    public function destroy ( User $currentUser, User $user )
    {
        return $currentUser->is_admin && $user->id !== $currentUser->id;
    }
}
