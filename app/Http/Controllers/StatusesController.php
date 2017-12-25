<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 添加微博、删除微博
 *
 * Class StatusesController
 *
 * @package App\Http\Controllers
 */
class StatusesController extends Controller
{
    /**
     * 只有登录用户才能访问控制器里面的方法
     *
     * StatusesController constructor.
     */
    public function __construct ()
    {
        $this->middleware( 'auth' );
    }

    /**
     * 创建微博
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store ( Request $request )
    {
        $this->validate( $request, [
            'content' => 'required|max:140'
        ] );

        Auth::user()->statuses()->create( [
            'content' => $request->input( 'content' )
        ] );

        return redirect()->back();
    }

    /**
     * 删除微博
     *
     * @param \App\Models\Status $status
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy ( Status $status )
    {
        $this->authorize( 'destroy', $status );

        $status->delete();
        session()->flash( 'success', '微博已被成功删除！' );

        return redirect()->back();
    }
}
