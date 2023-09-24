@extends('layouts.parent')

@section('title', 'マイページ')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <mypage-component :user="{{ $user }}"></mypage-componet>
    </div>
@endsection

@section('footer')
    @parent
@show