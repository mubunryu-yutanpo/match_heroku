こんにちは！
<br><br>
パスワードのリセットリクエストを受け付けました。以下のボタンをクリックしてパスワードをリセットしてください。
<br>

@component('mail::button', ['url' => $actionUrl])
    パスワードの再設定画面へ
@endcomponent

<br>
このリンクは30分間有効です。
<br>
よろしくお願いいたします。
<br>
ご注意：このメールに返信しないでください。
