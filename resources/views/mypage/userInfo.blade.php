@extends('layouts.parent')

@section('title', 'ユーザーインフォ')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <user-info-component 
            :auth_user_id="{{ Auth::id() }}"
            :user="{{ $user }}"
            :post_count="{{ $postCount }}"
            :apply_count="{{ $applyCount }}"
        ></user-info-component>
    </div>
@endsection

@section('footer')
    @parent
@show