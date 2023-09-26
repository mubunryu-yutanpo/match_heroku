@extends('layouts.parent')

@section('title', '案件一覧')

@section('description', 'matchに登録されている案件の一覧ページです。単発案件やサービス開発の依頼など様々な「欲しい」が集まっています。あなたも気軽に依頼や応募をしてみませんか？')

@section('header')
    @parent
@show

@section('main')

    <div id="app">
        <list-component />
    </div>

@endsection

@section('footer')
    @parent
@show