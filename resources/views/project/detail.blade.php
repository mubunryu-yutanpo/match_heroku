@extends('layouts.parent')

@section('title', '案件の詳細')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんの、案件詳細なんだな。
    </h1>

    <div id="app" class="">
        <detail-component :project_id="{{ $project_id }}" :user_id="{{ Auth::id() }}"></detail-component>
    </div>

@endsection

@section('footer')
    @parent
@show