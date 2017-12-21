<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * 注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create ()
    {
        return view( 'users.create' );
    }

    /**
     * 展示指定用户的信息
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show ( User $user )
    {
        return view( 'users.show', compact( 'user' ) );
    }

    /**
     * 验证并存储用户信息
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store ( Request $request )
    {
        $this->validate( $request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ] );

        $user = User::create( [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt( $request->password )
        ] );

        //注册完成后自动登录
        Auth::login( $user );

        session()->flash( 'success', '欢迎，您将在这开始一段新旅程...' );

        return redirect()->route( 'users.show', [ $user ] );
    }
}
