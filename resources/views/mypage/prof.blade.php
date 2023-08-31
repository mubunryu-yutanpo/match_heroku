@extends('layouts.parent')

@section('title', 'プロフィール編集')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんのプロフィール編集画面さ。
    </h1>

    <a href="{{ route('withdrow', $user->id) }}" class="c-link">退会はこちら</a>

@endsection

@section('footer')
    @parent
@show