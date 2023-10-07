<?php

namespace Tests\Unit;

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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception; 
use App\User;
use App\Apply;
use App\Project;
use App\Chat;
use App\DirectMessage;
use App\PublicMessage;
use App\Type;
use App\Notification;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Session::start();
    }



    /* ================================================================
        案件の作成・更新に関わるバリデーション（テストするメソッドは「create」）
    =================================================================*/

    // ＝＝＝＝＝＝＝＝ 異常系：タイトル未入力 ＝＝＝＝＝＝＝＝

    public function testCreateTitleError()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // テスト用のリクエストデータを作成
        $data = [
            'title' => null,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);
    
        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);
        
        // バリデーションエラーが期待される場合
        $response->assertSessionHasErrors([
            'title' => '入力必須です'
        ]);
    }


    // ＝＝＝＝＝＝＝＝ 異常系：typeが未選択 ＝＝＝＝＝＝＝＝

    public function testCreateTypeError1()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // テスト用のリクエストデータを作成（type未選択）
        $data = [
            'title' => 'Test Project',
            'type' => null, // type未選択
            // 他の必要なデータを追加
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);

        // リダイレクトされることを確認
        $response->assertStatus(302);

        // セッションにバリデーションエラーが含まれていることを確認
        $response->assertSessionHasErrors([
            'type' => '選択してください',
        ]);
    }


    // ＝＝＝＝＝＝＝＝ 異常系：ファイルサイズオーバー ＝＝＝＝＝＝＝＝

    public function testCreateImageError1()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // テスト用のHEIC形式の画像を作成
        $file = UploadedFile::fake()->create('test.heic', 100000000000, 'image/heic'); //（8MB以上）

        // テスト用のリクエストデータを作成
        $data = [
            'title' => 'Test Project',
            'thumbnail' => $file,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);
    
        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);
        
        // バリデーションエラーが期待される場合
        $response->assertSessionHasErrors([
            'thumbnail' => 'ファイルサイズは8MB以下にしてください',
        ]);
    }


    // ＝＝＝＝＝＝＝＝ 異常系：画像タイプ非対応 ＝＝＝＝＝＝＝＝

    public function testCreateImageError2()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // 存在しない（非対応の）画像形式
        $file = UploadedFile::fake()->create('test.hehehe', 555);

        // テスト用のリクエストデータを作成
        $data = [
            'title' => 'Test Project',
            'thumbnail' => $file,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);
    
        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);
        
        // バリデーションエラーが期待される場合
        $response->assertSessionHasErrors([
            'thumbnail' => 'ファイル形式はjpeg(jpg)、png、gif、heic（heif）が利用可能です',
        ]);
    }


    // ＝＝＝＝＝＝＝＝ 異常系：料金が安すぎる ＝＝＝＝＝＝＝＝

    public function testCreatePriceError1()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // テスト用のリクエストデータを作成（upperPriceが1未満）
        $data = [
            'title' => 'Test Project',
            'type' => 1, // 単発案件
            'upperPrice' => 0,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);

        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);

        // セッションにバリデーションエラーが含まれていることを確認
        $response->assertSessionHasErrors([
            'upperPrice' => '1,000円(1)未満は設定できません'
        ]);
    }

        // ＝＝＝＝＝＝＝＝ 異常系：料金が安すぎる2 ＝＝＝＝＝＝＝＝

        public function testCreatePriceError2()
        {
            // テスト用のユーザーを作成
            $user = factory(User::class)->create();
    
            // ログインさせる
            $this->actingAs($user);
    
            // テスト用のリクエストデータを作成（upperPriceが1未満）
            $data = [
                'title' => 'Test Project',
                'type' => 1, // 単発案件
                'lowerPrice' => 0,
            ];
            
            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);
    
            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/new', $data);
    
            // バリデーションエラーによるリダイレクトバック
            $response->assertStatus(302);
    
            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'lowerPrice' => '1,000円(1)未満は設定できません',
            ]);
        }


        // ＝＝＝＝＝＝＝＝ 異常系：料金が高すぎる ＝＝＝＝＝＝＝＝

        public function testCreatePriceError3()
        {
            // テスト用のユーザーを作成
            $user = factory(User::class)->create();
    
            // ログインさせる
            $this->actingAs($user);
    
            // テスト用のリクエストデータを作成（upperPriceが1未満）
            $data = [
                'title' => 'Test Project',
                'type' => 1, // 単発案件
                'lowerPrice' => 999999,
            ];
            
            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);
    
            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/new', $data);
    
            // バリデーションエラーによるリダイレクトバック
            $response->assertStatus(302);
    
            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'lowerPrice' => '999,998,000円(999998)以内で設定してください',
            ]);
        }


        // ＝＝＝＝＝＝＝＝ 異常系：料金が高すぎる ＝＝＝＝＝＝＝＝

        public function testCreatePriceError4()
        {
            // テスト用のユーザーを作成
            $user = factory(User::class)->create();
    
            // ログインさせる
            $this->actingAs($user);
    
            // テスト用のリクエストデータを作成（upperPriceが1未満）
            $data = [
                'title' => 'Test Project',
                'type' => 1, // 単発案件
                'upperPrice' => 100000000000,
            ];
            
            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);
    
            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/new', $data);
    
            // バリデーションエラーによるリダイレクトバック
            $response->assertStatus(302);
    
            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'upperPrice' => '999,999,000円(999999)以内で設定してください',
            ]);
        }


        // ＝＝＝＝＝＝＝＝ 異常系：説明文が未記入 ＝＝＝＝＝＝＝＝

        public function testCreateContentError1()
        {
            // テスト用のユーザーを作成
            $user = factory(User::class)->create();

            // ログインさせる
            $this->actingAs($user);

            // テスト用のリクエストデータを作成（contentがnull）
            $data = [
                'title' => 'Test Project',
                'type' => 1,
                'content' => null,
            ];

            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);

            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/new', $data);

            // リダイレクトされることを確認
            $response->assertStatus(302);

            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'content' => '入力必須です'
            ]);
        }

        // ＝＝＝＝＝＝＝＝ 異常系：説明文が未記入 ＝＝＝＝＝＝＝＝

        public function testCreateContentError2()
        {
            // テスト用のユーザーを作成
            $user = factory(User::class)->create();

            // ログインさせる
            $this->actingAs($user);

            // 2001文字以上の長さの文字列を生成
            $excessiveContent = str_repeat('ほ', 5000);

            // テスト用のリクエストデータを作成（contentがnull）
            $data = [
                'title' => 'Test Project',
                'type' => 1,
                'content' => $excessiveContent,
            ];

            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);

            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/new', $data);

            // リダイレクトされることを確認
            $response->assertStatus(302);

            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'content' => '2000文字以内で入力してください'
            ]);
        }


    /* ================================================================
        プロフィールに関するバリデーション（テストするメソッドは「profUpdate」）
    =================================================================*/
    
    // ＝＝＝＝＝＝＝＝ 異常系：自己紹介文の文字数オーバー ＝＝＝＝＝＝＝＝

    public function testProfUpdateIntroductionError(){
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();
    
        // ログインさせる
        $this->actingAs($user);

        // 2001文字以上の長さの文字列を生成
        $excessiveIntroduction = str_repeat('の', 1200);

    
        // テスト用のリクエストデータを作成（upperPriceが1未満）
        $data = [
            'introduction' => $excessiveIntroduction,
        ];
            
            // CSRFトークンの検証を一時的に無効化
            $this->withoutMiddleware(VerifyCsrfToken::class);
    
            // テスト用のファイルアップロードをシミュレート
            $response = $this->post('/prof/'. $user->id . '/edit', $data);
    
            // バリデーションエラーによるリダイレクトバック
            $response->assertStatus(302);
    
            // セッションにバリデーションエラーが含まれていることを確認
            $response->assertSessionHasErrors([
                'introduction' => '300文字以内で入力してください',
            ]);
    }

    // ＝＝＝＝＝＝＝＝ 異常系：ファイルサイズオーバー ＝＝＝＝＝＝＝＝
    
    public function testProfUpdateImageError1()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // テスト用のHEIC形式の画像を作成
        $file = UploadedFile::fake()->create('test.heic', 100000000000, 'image/heic'); //（8MB以上）

        // テスト用のリクエストデータを作成
        $data = [
            'thumbnail' => $file,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/prof/'. $user->id . '/edit', $data);
    
        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);
        
        // バリデーションエラーが期待される場合
        $response->assertSessionHasErrors([
            'thumbnail' => 'ファイルサイズは8MB以下にしてください',
        ]);
    }


    // ＝＝＝＝＝＝＝＝ 異常系：画像タイプ非対応 ＝＝＝＝＝＝＝＝

    public function testProfUpdateImageError2()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // 存在しない（非対応の）画像形式
        $file = UploadedFile::fake()->create('test.hehehe', 555);

        // テスト用のリクエストデータを作成
        $data = [
            'title' => 'Test Project',
            'thumbnail' => $file,
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/prof/'. $user->id . '/edit', $data);
    
        // バリデーションエラーによるリダイレクトバック
        $response->assertStatus(302);
        
        // バリデーションエラーが期待される場合
        $response->assertSessionHasErrors([
            'thumbnail' => 'ファイル形式はjpeg(jpg)、png、gif、heic（heif）が利用可能です',
        ]);
    }



}
