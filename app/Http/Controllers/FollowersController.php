<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class FollowersController
 *
 * @package App\Http\Controllers
 */
class FollowersController extends Controller
{
    /**
     * 登录之后才能访问控制器中的方法
     *
     * FollowersController constructor.
     */
    public function __construct ()
    {
        $this->middleware( 'auth' );
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store ( User $user )
    {
        if ( Auth::user()->id === $user->id )
            return redirect()->route( 'home' );

        if ( !Auth::user()->isFollowing( $user->id ) )
            Auth::user()->follow( $user->id );

        return redirect()->route( 'users.show', $user->id );
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy ( User $user )
    {
        if ( Auth::user()->id === $user->id )
            return redirect()->route( 'home' );

        if ( Auth::user()->isFollowing( $user->id ) )
            Auth::user()->unfollow( $user->id );

        return redirect()->route( 'users.show', $user->id );
    }
}
