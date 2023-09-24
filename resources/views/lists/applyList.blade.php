@extends('layouts.parent')

@section('title', '応募案件一覧')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <apply-list-component :user_id="{{ $user_id }}"></apply-list-component>
    </div>
@endsection

@section('footer')
    @parent
@show