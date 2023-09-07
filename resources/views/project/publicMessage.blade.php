@extends('layouts.parent')

@section('title', '案件のパブリックメッセージ')

@section('header')
    @parent
@show

@section('main')
    <p>{{ $project->title }}のメッセージ一覧</p>
    <p>{{ $project->user->name }}</p>

    <div id="app" class="">
        <public-message-component :project="{{ $project }}" :user_id="{{ $user_id }}" :seller_id="{{ $seller_id }}"></public-message-component>
    </div>
@endsection

@section('footer')
    @parent
@show