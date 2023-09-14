@extends('layouts.parent')

@section('title', 'DM一覧')

@section('header')
    @parent
@show

@section('main')
    <p>DM一覧ページ</p>
    <span>{{ $user_id }}</span>
    <div id="app">
        <direct-message-list-component  :user_id="{{ $user_id }}"></direct-message-list-component>
    </div>
@endsection

@section('footer')
    @parent
@show