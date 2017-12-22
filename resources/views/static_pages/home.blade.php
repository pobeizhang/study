@extends('layouts.default')
@section('title','主页')
@section('content')
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
@stop
