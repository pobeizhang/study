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
     * UsersController constructor.
     */
    public function __construct ()
    {
        //除了except数组中的方法，其他方法都需要登陆后才能访问
        $this->middleware( 'auth', [
            'except' => [ 'index', 'show', 'create', 'store' ]
        ] );

        //只允许未登录用户访问的动作
        $this->middleware( 'guest', [
            'only' => [ 'create' ]
        ] );
    }

    /**
     * 显示用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ()
    {
        $users = User::paginate( 10 );
        return view( 'users.index', compact( 'users' ) );
    }

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
     * @param \App\Models\User $user
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
            'name' => trim( $request->name ),
            'email' => trim( $request->email ),
            'password' => trim( bcrypt( $request->password ) )
        ] );

        //注册完成后自动登录
        Auth::login( $user );

        session()->flash( 'success', '欢迎，您将在这开始一段新旅程...' );

        return redirect()->route( 'users.show', [ $user ] );
    }

    /**
     * 更新用户信息
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit ( User $user )
    {
        $this->authorize( 'update', $user );

        return view( 'users.edit', compact( 'user' ) );
    }

    /**
     * 更新用户信息
     *
     * @param \App\Models\User         $user
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update ( User $user, Request $request )
    {
        $this->authorize( 'update', $user );

        $this->validate( $request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ] );

        $data = [];
        $data[ 'name' ] = trim( $request->name );

        if ( $request->password )
            $data[ 'password' ] = bcrypt( trim( $request->password ) );

        $user->update( $data );

        session()->flash( 'success', '用户信息更新成功' );

        return redirect()->route( 'users.show', $user->id );
    }

    /**
     * 删除指定用户
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy ( User $user )
    {
        $this->authorize( 'destroy', $user );

        $user->delete();

        session()->flash( 'success', '成功删除用户' );

        return back();
    }
}
