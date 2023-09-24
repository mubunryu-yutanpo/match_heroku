@extends('layouts.parent')

@section('title', 'メッセージをした案件一覧')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <public-message-list-component :user_id="{{ $user_id }}"></public-message-list-component>
    </div>
@endsection

@section('footer')
    @parent
@show