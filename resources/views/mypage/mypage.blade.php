@extends('layouts.parent')

@section('title', 'マイページ')

@section('header')
    @parent
@show

@section('main')
    <h1>私がマイページさ！</h1>
    <img src="{{ $user->avatar }}" alt="">
@endsection

@section('footer')
    @parent
@show