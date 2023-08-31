@extends('layouts.parent')

@section('title', '案件の編集')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんの、案件の編集・削除ページさ。
        案件のIDは{{ $project }}です。
    </h1>

@endsection

@section('footer')
    @parent
@show