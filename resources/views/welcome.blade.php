@extends('layouts.parent')

@section('title', 'match')

@section('description', 'matchはカンタンにエンジニア向け案件の依頼・応募ができるサービスです。単発案件やサービス開発の依頼など、エンジニアに関する様々な「欲しい」をやり取りできます')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <welcome-component></welcome-component>
    </div>
@endsection

@section('footer')
    @parent
@show