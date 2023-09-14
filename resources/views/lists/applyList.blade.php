@extends('layouts.parent')

@section('title', '同意一覧')

@section('header')
    @parent
@show

@section('main')
    <p>同意一覧ページ</p>
    <span>{{ $user_id }}</span>
    <div id="app">
        <apply-list-component :user_id="{{ $user_id }}"></apply-list-component>
    </div>
@endsection

@section('footer')
    @parent
@show