<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Middleware\VerifyCsrfToken;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Apply;
use App\Project;
use App\Chat;
use App\DirectMessage;
use App\PublicMessage;
use App\Type;
use App\Notification;



class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /* ================================================================
        メソッド名：new
    =================================================================*/
    public function testNew()
    {
        // テスト用の認証済みユーザーを作成
        $user = factory(User::class)->create();
        $this->actingAs($user); // ユーザーを認証状態にする

        // メソッドを呼び出し
        $response = $this->get('/new');

        // レスポンスのステータスコードを確認
        $response->assertStatus(200);
        
        // ビューが正しく表示されることを確認
        $response->assertViewIs('project.new');

        // ビューに渡されるデータを確認
        $response->assertViewHas('user', $user);
        $response->assertViewHas('projectType', Type::all());
    }

    /* ================================================================
        メソッド名：create
    =================================================================*/
    public function testCreate()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();
        $this->actingAs($user); // ユーザーを認証状態にする
    
        // Typeを作成（例：idが1のTypeを作成）
        $type = factory(Type::class)->create(['id' => 1]);

        // テスト用のリクエストデータを作成
        $data = [
            'title' => 'テスト案件',
            'type' => $type->id,
            'upperPrice' => 100,
            'lowerPrice' => 50,
            'content' => 'これはテスト案件です。',
        ];
    
        // メール送信を無効化
        Mail::fake();
    
        // ダミーの画像ファイルを作成してストレージに保存
        Storage::fake('public'); // 'public' ディスクを使用することを指定
        $file = UploadedFile::fake()->image('test-image.jpg');
        $file->storeAs('public/uploads', 'test-image.jpg');
    
        // Project インスタンスを作成してデータを埋め込む
        $project = new Project;
    
        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);
    
        // リダイレクトされることを確認
        $response->assertRedirect('/');
    
        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', '案件を投稿しました！');
    
        // データベースに新しい案件が保存されたことを確認
        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'upperPrice' => $data['upperPrice'] * 1000, // 金額を変換した値に合わせる
            'lowerPrice' => $data['lowerPrice'] * 1000, // 金額を変換した値に合わせる
            'content' => $data['content'],
        ]);
    
        // ストレージに画像が保存されたことを確認
        Storage::disk('public')->assertExists('uploads/test-image.jpg');
    }    


}
