@extends('layouts.parent')

@section('title', 'メール認証画面')

@section('header')
    @parent
@show

@section('main')

    <div class="p-verify c-box">
        
        <h2 class="p-verify c-title">
            <i class="fa-solid fa-user-check c-icon c-icon--title"></i>
            ユーザー認証
        </h2>

        <div class="p-verify__container">

            @if (session('resent'))
                <div class="p-alert is-success" role="alert">
                    メールを送信しました！
                </div>
            @endif

            <p class="p-verify__text c-text">メールを送信しました。メール内のリンクをクリックし、本登録をお願いします。</p>
            <p class="p-verify__text c-text">メールが届いていない場合は、
                <a href="{{ route('verification.resend') }}" class="p-verify__link c-link">ここをクリックしてください。</a>
                再送いたします。
            </p>
        </div>

    </div>
</section>

@endsection

@section('footer')
    @parent
@show