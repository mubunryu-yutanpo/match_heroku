@extends('layouts.parent')

@section('title', '退会処理')

@section('header')
    @parent
@show

@section('main')
        <form action="{{ route('destroy', $user->id) }}" method="post" class="p-withdraw c-box--form">
            @csrf

            <h2 class="p-withdraw__title c-title">
                <i class="fa-solid fa-user-slash c-icon c-icon--title"></i>
                退会
            </h2>
            <div class="p-withdraw__container">
                <p class="p-withdraw__text c-text">※すべてのデータが消去されます。この操作は取り消せません。</p>
                <button type="submit" class="p-withdraw__button c-button" onclick='return confirm("退会します。よろしいですか？");'>
                    退会する
                </button>
            </div>
        </form>
@endsection

@section('footer')
    @parent
@show