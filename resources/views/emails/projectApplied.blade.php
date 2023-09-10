
<p>案件への応募が完了しました。</p>
<p>案件タイトル: {{ $project->title }}</p>
<p>案件内容: {{ $project->content }}</p>
<p>
    メッセージを送りましょう！<br>
    <a href="{{ route('d.message', ['auth_user_id' => $user->id, 'user_id' => $post_user->id]) }}">メッセージ画面へ移動する</a>
</p>
