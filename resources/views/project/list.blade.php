@extends('layouts.parent')

@section('title', '案件一覧')

@section('description', 'matchに登録されている案件の一覧ページです。単発案件やサービス開発の依頼など様々な「欲しい」が集まっています。あなたも気軽に依頼や応募をしてみませんか？')

@section('header')
    @parent
@show

@section('main')

    <h1 class="">案件一覧ページだぜ</h1>
    <div id="app">
        Component
        <example-component />
    </div>
    <p>
        <a href="{{ route('detail', 1) }}">案件の詳細</a>
        はこちら
    </p>
    <p>
        <a href="{{ route('project.edit', 1) }}">案件の編集</a>
        はコチラ
    </p>

@endsection

@section('footer')
    @parent
@show