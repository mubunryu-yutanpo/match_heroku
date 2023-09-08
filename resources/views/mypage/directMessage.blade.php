@extends('layouts.parent')

@section('title', 'DM')

@section('header')
    @parent
@show

@section('main')
    <h1>DM一覧画面くん</h1>
    <p>{{ $user->name }}さんのメッセージ一覧。的なやつ</p>
    <p>チャットID：{{ $chat->id }}</p>
    <p>チャットのユーザーID：{{ $chat->user1_id }}と{{  $chat->user2_id }}</p>

    <div id="app">
        <direct-message-component :user="{{ $user }}" :chat="{{ $chat }}"></direct-message-component>
    </div>
@endsection

@section('footer')
    @parent
@show