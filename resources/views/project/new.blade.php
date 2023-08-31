@extends('layouts.parent')

@section('title', '新規案件登録')

@section('header')
    @parent
@show

@section('main')
    <h1>
        NEW案件を{{ $user->name }}さんが登録したがってるようです。
    </h1>

@endsection

@section('footer')
    @parent
@show