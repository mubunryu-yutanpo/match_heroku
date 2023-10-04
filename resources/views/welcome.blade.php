@extends('layouts.parent')

@section('header')
    @parent
@show

@section('main')
    <h1>ウェルカム</h1>

    <div id="app">
        <welcome-component></welcome-component>
    </div>
@endsection

@section('footer')
    @parent
@show