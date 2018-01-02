<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    /**
     * 显示主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home ()
    {
        $feed_items = [];
        if ( Auth::check() ) {
            $feed_items = Auth::user()->feed()->paginate( 30 );
        }
        return view( 'static_pages.home', compact( 'feed_items' ) );
    }

    /**
     * 显示帮助页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function help ()
    {
        return view( 'static_pages.help' );
    }

    /**
     * 显示关于页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about ()
    {
        return view( 'static_pages.about' );
    }
}
