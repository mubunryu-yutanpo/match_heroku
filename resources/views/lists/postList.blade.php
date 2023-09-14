@extends('layouts.parent')

@section('title', '投稿一覧')

@section('header')
    @parent
@show

@section('main')
    <p>投稿一覧ページ</p>
    <span>{{ $user_id }}</span>
    <div id="app">
        <post-list-component :user_id="{{ $user_id }}"></post-list-component>
    </div>
@endsection

@section('footer')
    @parent
@show