@extends('layouts.default')
@section('title','主页')
@section('content')
    @if(Auth::check())
        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('shared._status_form')
                </section>
                <h3>微博列表</h3>
                @include('shared._feed')
            </div>
            <aside class="col-md-4">
                <section class="user_info">
                    @include('shared._user_info', ['user' => Auth::user()])
                </section>
                <section class="stats">
                    @include('shared._stats', ['user' => Auth::user()])
                </section>
            </aside>
        </div>
    @else
        <div class="jumbotron">
            <h1>Hello ArleyDu</h1>
            <p class="lead">
                你现在所看到的是 <a href="{{ route('home') }}">ArleyDu</a> 主页。
            </p>
            <p>
                一切，将从这里开始。
            </p>
            @if(!Auth::check())
                <p>
                    <a class="btn btn-lg btn-success" href="{{ route('users.create') }}" role="button">现在注册</a>
                </p>
            @endif
        </div>
    @endif
@stop
