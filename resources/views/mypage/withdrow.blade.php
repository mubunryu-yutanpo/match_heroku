@extends('layouts.parent')

@section('title', '退会処理')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんや。本当に退会しちゃうのかい？
    </h1>

    <form action="{{ route('destroy', $user->id) }}" method="post" class="p-form">
        @csrf
        <div class="p-submit">
            <button type="submit" class="c-button p-submit__button" onclick='return confirm("退会します。よろしいですか？");'>
                退会する
            </button>
        </div>
    </form>


@endsection

@section('footer')
    @parent
@show