@extends('layouts.parent')

@section('title', 'プロフィール編集')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんのプロフィール編集画面さ。
    </h1>

    <form action="{{ route('prof.update', $user->id) }}" method="post" class="p-form" enctype="multipart/form-data">
        @csrf

        <div class="c-title p-form__title">新規ユーザー登録</div>

        <!-- ユーザー名 -->
        <div class="p-form__container">
            <label for="name" class="c-label p-form__label">名前:</label>
                <input id="name" type="text" class="c-input p-form__input @error('name') valid-error @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- メールアドレス -->
        <div class="p-form__container">
            <label for="email" class="c-label p-form__label">メールアドレス:</label>
                <input id="email" type="email" class="c-input p-form__input @error('email') valid-error @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <div id="app">
            <avatar-preview-component :user="{{ $user }}"></avatar-preview-component>
        </div>

        <!-- 自己紹介文 -->
        <div class="p-form__container">
            <label for="introduction" class="c-label p-form__label">自己紹介文:</label>
                <textarea name="introduction" id="introduction" cols="30" rows="10" class="c-textarea p-form__textarea" placeholder="300文字以内で入力してください">{{ old('introduction', $user->introduction) }}</textarea>
                @error('introduction')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">更新して登録</button>
        </div>

    </form>

    <a href="{{ route('withdraw', $user->id) }}" class="c-link">退会はこちら</a>

@endsection

@section('footer')
    @parent
@show