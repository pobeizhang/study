<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
