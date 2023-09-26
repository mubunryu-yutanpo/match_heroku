@extends('layouts.parent')

@section('title', 'チャット')

@section('header')
    @parent
@show

@section('main')
    <div id="app">
        <direct-message-component :user="{{ $user }}" :chat="{{ $chat }}"></direct-message-component>
    </div>

@endsection

@section('footer')
    @parent
@show