<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            'except' => [ 'index', 'show', 'create', 'store', 'confirmEmail' ]
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
        $statuses = $user->statuses()->orderBy( 'created_at', 'desc' )->paginate( 30 );

        return view( 'users.show', compact( 'user', 'statuses' ) );
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

        //注册完成后发送确认激活邮件,激活后才能进行登录
        $this->sendEmailConfirmationTo( $user );

        session()->flash( 'success', '验证邮件已发送到你的注册邮箱上，请注意查收。' );

        return redirect()->route( 'home' );
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

        return redirect()->back();
    }

    /**
     * 发送邮件
     *
     * @param $user
     */
    protected function sendEmailConfirmationTo ( $user )
    {
        $view = 'emails.confirm';
        $data = compact( 'user' );
        $from = 'arleydu@163.com';
        $name = 'ArleyDu';
        $to = $user->email;
        $subject = "感谢注册 ArleyDu 应用！请确认你的邮箱。";

        Mail::send( $view, $data, function ( $message ) use ( $from, $name, $to, $subject ) {
            $message->from( $from, $name )->to( $to )->subject( $subject );
        } );
    }

    /**
     * 点击激活链接，激活账户
     *
     * @param $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail ( $token )
    {
        $user = User::where( 'activation_token', $token )->firstOrFail();

        $user->activation_token = null;
        $user->activated = true;
        $user->save();

        Auth::login( $user );

        session()->flash( 'success', '恭喜你，激活成功' );

        return redirect()->route( 'users.show', $user );
    }

    /**
     * 显示关注着的列表页面
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followings ( User $user )
    {
        $users = $user->followings()->paginate( 30 );
        $title = '关注的人';

        return view( 'users.show_follow', compact( 'users', 'title' ) );
    }

    /**
     * 显示粉丝列表页面
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers ( User $user )
    {
        $users = $user->followers()->paginate( 30 );
        $title = '被关注的人';

        return view( 'users.show_follow', compact( 'users', 'title' ) );
    }
}
