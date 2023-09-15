@extends('layouts.parent')

@section('title', 'マイページ')

@section('header')
    @parent
@show

@section('main')
    <h1>私がマイページさ！</h1>
    <div id="app">
        <mypage-component :user="{{ $user }}"></mypage-componet>
    </div>
@endsection

@section('footer')
    @parent
@show