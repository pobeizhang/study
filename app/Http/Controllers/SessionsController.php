<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 登录、退出、会话管理处理类
 *
 * Class SessionsController
 *
 * @package App\Http\Controllers
 */
class SessionsController extends Controller
{
    public function __construct ()
    {
        $this->middleware( 'guest', [
            'only' => [ 'create' ]
        ] );
    }

    /**
     * 显示登录页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create ()
    {
        return view( 'sessions.create' );
    }

    /**
     * 创建新会话(登录)
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store ( Request $request )
    {
        $credentials = $this->validate( $request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ] );

        if ( Auth::attempt( $credentials, $request->has( 'remember' ) ) ) {

            session()->flash( 'success', '欢迎回来' );

            return redirect()->intended( route( 'users.show', [ Auth::user() ] ) );
        } else {

            session()->flash( 'danger', '很抱歉，你的邮箱和密码不匹配' );

            return redirect()->back();
        }
    }

    /**
     * 销毁会话(退出登录)
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy ()
    {
        Auth::logout();

        session()->flash( 'success', '你已成功退出' );

        return redirect( 'login' );
    }
}
