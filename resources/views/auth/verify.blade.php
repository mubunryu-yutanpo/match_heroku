@extends('layouts.parent')

@section('title', 'メール認証画面')

@section('header')
    @parent
@show

@section('main')

<section class="p-page-visual">
    
    <div class="p-page-visual__container">
        
        <div class="c-title">新規ユーザー登録</div>

        <div class="p-container">

            @if (session('resent'))
                <div class="p-alert is-success" role="alert">
                    メールを送信しました！
                </div>
            @endif

            <p class="c-text">メールを送信しました。メール内のリンクをクリックし、本登録をお願いします。</p>
            <p class="c-text">メールが届いていない場合は、
                <a href="{{ route('verification.resend') }}" class="c-link">ここをクリックしてください。</a>
                再送いたします。
            </p>
        </div>

    </div>



    <div class="p-form__container">
        <label for="name" class="c-label">名前:</label>
        <input id="name" type="text" class="c-input @error('name') valid-error @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
            <span class="c-error-text" role="alert">
                {{ $message }}
            </span>
        @enderror
    </div>

</section>

@endsection

@section('footer')
    @parent
@show