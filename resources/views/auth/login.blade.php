@extends('layouts.parent')

@section('title', 'ログイン')

@section('header')
    @parent
@show

@section('main')

<section class="p-page-visual">
    <form action="{{ route('login') }}" method="post" class="p-form">
        @csrf

        <div class="c-title p-form__title">ログイン</div>

        <div class="p-form__container">
            <label for="email" class="c-label">メールアドレス:</label>
            <input id="email" type="text" class="c-input @error('email') valid-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
            <div class="">

                <div class="p-remember">
                    <input class="c-input p-remember__input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="c-label p-remember__label" for="remember">
                        ログインを保持する
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <div class="p-forgot">
                        <a class="c-link p-forgot__link" href="{{ route('password.request') }}">
                            パスワードをお忘れの方はこちら
                        </a>
                    </div>
                @endif

            </div>
        </div>

        <div class="p-submit">
            <button type="submit" class="c-button p-submit__button">ログインする</button>
        </div>


    </form>
</section>

@endsection

@section('footer')
    @parent
@show