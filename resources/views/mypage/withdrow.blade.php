@extends('layouts.parent')

@section('title', '退会処理')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんや。本当に退会しちゃうのかい？
    </h1>

@endsection

@section('footer')
    @parent
@show