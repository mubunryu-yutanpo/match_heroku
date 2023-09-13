@extends('layouts.parent')

@section('title', 'ユーザー登録')

@section('header')
    @parent
@show

@section('main')

<section class="p-page-visual">
    <form action="{{ route('register') }}" method="post" class="p-form">
        @csrf

        <div class="c-title p-form__title">新規ユーザー登録</div>

        <div class="p-form__container">
            <label for="name" class="c-label">名前:</label>
            <input id="name" type="text" class="c-input @error('name') valid-error @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="20文字以内">
            @error('name')
                <span class="c-error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-form__container">
            <label for="email" class="c-label">メールアドレス:</label>
            <input id="email" type="email" class="c-input @error('email') valid-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="c-error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-form__container">
            <label for="password" class="c-label">パスワード:</label>
            <input id="password" type="password" class="c-input @error('password') valid-error @enderror" name="password" required autocomplete="current-password" placeholder="半角英数字8文字以上">
            @error('password')
                <span class="c-error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-form__container">
            <label for="password-confirm" class="c-label">パスワード（再入力）:</label>
            <input id="password-confirm" type="password" class="c-input @error('password-confirm') valid-error @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="半角英数字8文字以上">
            @error('password-confirm')
                <span class="c-error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button type="submit" class="c-button p-submit__button">登録する</button>
        </div>


    </form>
</section>

@endsection

@section('footer')
    @parent
@show