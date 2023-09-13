@extends('layouts.parent')

@section('title', 'ユーザーインフォ')

@section('header')
    @parent
@show

@section('main')
    <h1>Userのインフォさ</h1>
    <h2>{{ $user->name }}さんだよ。</h2>
    <img src="{{ $user->avatar }}" alt="">
    <div class="">
        <p class="">案件投稿数</p>
        <p class="">{{ $postCount }}</p>
    </div>
    <div class="">
        <p class="">応募した件数</p>
        <p class="">{{ $applyCount }}</p>
    </div>
    <div class="">
        <a href="{{ route('d.message', ['auth_user_id' => Auth::id(), 'user_id' => $user->id ] ) }}" class="">
            メッセージを送る
        </a>
    </div>
@endsection

@section('footer')
    @parent
@show