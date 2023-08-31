@extends('layouts.parent')

@section('title', 'DM')

@section('header')
    @parent
@show

@section('main')
    <h1>DM一覧画面くん</h1>
    <p>{{ $user->name }}さんのメッセージ一覧。的なやつ</p>
    <p>$userにメッセージも紐づけてないし、データ取得等の処理もまだ</p>
@endsection

@section('footer')
    @parent
@show