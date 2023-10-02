<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Mail;
use App\User;

class MypageControllerTest extends TestCase
{

    // テストごとにデータベースをリフレッシュ
    use RefreshDatabase;

    
    /* ================================================================
        メソッド名：prof
    =================================================================*/

    public function testProf()
    {
        // テストユーザーを作成（モデルファクトリーを使用）
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);
    
        // コントローラーメソッドを呼び出し
        $response = $this->get('/prof/' . $user->id);
    
        // レスポンスのステータスコードを確認
        $response->assertStatus(200);

        // ビューが正しく表示されることを確認
        $response->assertViewIs('mypage.prof');
    
        // ビューに渡されるデータを確認
        $response->assertViewHas('user', $user);
    }


    /* ================================================================
        メソッド名：profUpdate
    =================================================================*/
    public function testProfUpdate()
    {
        // テスト用のユーザーを作成
        $password = 'testpassword'; // ユーザー登録時に設定したパスワードをここで指定する
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);
        $this->actingAs($user);

        // テスト用のリクエストデータを作成（パスワードはユーザー登録時に設定したものと同じ）
        $data = [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => $password,
            'password_confirmation' => $password,
            'introduction' => 'テスト自己紹介',
            // 画像などのパラメーターも必要に応じて追加
        ];

        // メール送信を無効化
        Mail::fake();

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // エンドポイントを呼び出す
        $response = $this->post('/prof/' . $user->id . '/edit', $data);

        // リダイレクトされることを確認
        $response->assertRedirect('/mypage');

        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', 'プロフィールを更新しました！');

        // ユーザー情報が変更されていることを確認
        $updatedUser = User::find($user->id);
        $this->assertEquals('テストユーザー', $updatedUser->name);
        $this->assertEquals('test@example.com', $updatedUser->email);
        $this->assertEquals('テスト自己紹介', $updatedUser->introduction);
    }

    /* ================================================================
        メソッド名：withdraw
    =================================================================*/

    public function testWithdraw()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);

        // メソッドを呼び出し
        $response = $this->get('/withdraw/' . $user->id);

        // レスポンスのステータスコードを確認
        $response->assertStatus(200);

        // ビューが正しく表示されることを確認
        $response->assertViewIs('mypage.withdraw');

        // ビューに渡されるデータを確認
        $response->assertViewHas('user', $user);
    }

    /* ================================================================
        メソッド名：destroy
    =================================================================*/
    public function testDestroy(){
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のリクエストを送信
        $response = $this->post('/withdraw/' . $user->id . '/destroy');

        // ユーザーが削除されたことを確認
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // リダイレクトされることを確認
        $response->assertRedirect('/');

        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', '退会しました');
        $response->assertSessionHas('flash_message_type', 'success');
    }

    /* ================================================================
        メソッド名：postList
    =================================================================*/
    public function testPostList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);
    
        // メソッドを呼び出し
        $response = $this->get('/postList/' . $user->id);
    
        // レスポンスのステータスコードを確認
        $response->assertStatus(200);
    
        // ビューが正しく表示されることを確認
        $response->assertViewIs('lists.postList');
    
        // ビューに渡されるデータを確認
        $response->assertViewHas('user_id', $user->id);
    }
    
    /* ================================================================
        メソッド名：applyList
    =================================================================*/
    public function testApplyList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);
    
        // メソッドを呼び出し
        $response = $this->get('/applyList/' . $user->id);
    
        // レスポンスのステータスコードを確認
        $response->assertStatus(200);
    
        // ビューが正しく表示されることを確認
        $response->assertViewIs('lists.applyList');
    
        // ビューに渡されるデータを確認
        $response->assertViewHas('user_id', $user->id);
    }


    /* ================================================================
        メソッド名：publicMessageList
    =================================================================*/
    public function testPublicMessageList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);
    
        // メソッドを呼び出し
        $response = $this->get('/publicMessageList/' . $user->id);
    
        // レスポンスのステータスコードを確認
        $response->assertStatus(200);
    
        // ビューが正しく表示されることを確認
        $response->assertViewIs('lists.publicMessageList');
    
        // ビューに渡されるデータを確認
        $response->assertViewHas('user_id', $user->id);
    }


    /* ================================================================
        メソッド名：directMessageList
    =================================================================*/
    public function testDirectMessageList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストユーザーをログインさせる
        $this->actingAs($user);
    
        // メソッドを呼び出し
        $response = $this->get('/directMessageList/' . $user->id);
    
        // レスポンスのステータスコードを確認
        $response->assertStatus(200);
    
        // ビューが正しく表示されることを確認
        $response->assertViewIs('lists.directMessageList');
    
        // ビューに渡されるデータを確認
        $response->assertViewHas('user_id', $user->id);
    }


}
