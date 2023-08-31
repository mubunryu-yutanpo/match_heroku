@extends('layouts.parent')

@section('title', '案件の詳細')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんの、案件詳細なんだな。
    </h1>
    <strong>ID:{{ $project }}の案件なのよ！</strong>

@endsection

@section('footer')
    @parent
@show