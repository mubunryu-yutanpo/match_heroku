<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Project;
use App\Type;
use App\Apply;


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
        $this->actingAs($user); // ユーザーをログインさせる

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
        $this->actingAs($user); // ユーザーをログインさせる
    
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
        
        // Project インスタンスを作成してデータを埋め込む
        $project = new Project;
    
        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // テスト用のファイルアップロードをシミュレート
        $response = $this->post('/new', $data);
    
        // リダイレクトされることを確認
        $response->assertRedirect('/mypage');
    
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
    
    }    

    /* ================================================================
        メソッド名：detail
    =================================================================*/
    public function testProjectDetail()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // プロジェクト詳細ページにアクセス
        $response = $this->get('/project/'. $project->id . '/detail');

        // レスポンスのステータスコードを確認
        $response->assertStatus(200);

        // ビュー名を確認
        $response->assertViewIs('project.detail');

        // ビューにデータが正しく渡されていることを確認
        $response->assertViewHas('user', $user);
        $response->assertViewHas('project', $project);
    }

    /* ================================================================
        メソッド名：edit
    =================================================================*/
    public function testProjectEdit()
    {
        // テスト用のユーザー、プロジェクト、プロジェクトタイプを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $projectType = factory(Type::class, 1)->create();

        // ログインさせる
        $this->actingAs($user);

        // プロジェクト編集ページにアクセス
        $response = $this->get('/edit/project/' . $project->id);

        // レスポンスのステータスコードを確認
        $response->assertStatus(200);

        // ビュー名を確認
        $response->assertViewIs('project.edit');

        // ビューにデータが正しく渡されていることを確認
        $response->assertViewHas('user', $user);
        $response->assertViewHas('project', $project);
    }

    /* ================================================================
        メソッド名：projectUpdate
    =================================================================*/
    public function testProjectUpdate()
    {
        // テスト用のユーザー、プロジェクト、プロジェクトタイプを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['user_id' => $user->id]);
        $projectType = factory(Type::class)->create();

        // ログインさせる
        $this->actingAs($user);

        // 正常なリクエストデータを作成
        $requestData = [
            'title' => '更新後のタイトル',
            'type' => $projectType->id,
            'upperPrice' => 500,
            'lowerPrice' => 250,
            'content' => '更新後のコンテンツ',
        ];

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // プロジェクト更新リクエストを送信
        $response = $this->post('/edit/project/' . $project->id . '/update', $requestData);

        // レスポンスのステータスコードを確認
        $response->assertStatus(302); // リダイレクト

        // リダイレクト先を確認
        $response->assertRedirect('/mypage');

        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', '情報を更新しました！');
        
        // プロジェクトが正しく更新されたことを確認
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => $requestData['title'],
            'type' => $requestData['type'],
            'upperPrice' => $requestData['upperPrice'] * 1000, // 金額を変換した値に合わせる
            'lowerPrice' => $requestData['lowerPrice'] * 1000, // 金額を変換した値に合わせる
            'content' => $requestData['content'],
        ]);

    }

    /* ================================================================
        メソッド名：projectDelete
    =================================================================*/
    public function testProjectDelete()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['user_id' => $user->id]);

        // ログインさせる
        $this->actingAs($user);

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // プロジェクト削除リクエストを送信
        $response = $this->post('edit/project/' . $project->id . '/delete');

        // レスポンスのステータスコードを確認
        $response->assertStatus(302); // リダイレクト

        // リダイレクト先を確認
        $response->assertRedirect('/mypage');

        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', '削除しました！');
        
        // プロジェクトが削除されたことを確認
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /* ================================================================
        メソッド名：apply
    =================================================================*/
    public function testProjectApply()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();
        
        // 認証状態にする
        $this->actingAs($user);

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // プロジェクト応募リクエストを送信
        $response = $this->post('/project/'. $project->id . '/'. $user->id. '/apply');

        // レスポンスのステータスコードを確認
        $response->assertStatus(302); // リダイレクト

        // リダイレクト先を確認
        $response->assertRedirect('/mypage');

        // セッションにフラッシュメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', '応募完了！メールをご確認ください');
        $response->assertSessionHas('flash_message_type', 'success');
        
        // Applyテーブルにレコードが追加されたことを確認
        $this->assertDatabaseHas('apply', [
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);
    }

    public function testProjectApplyToAlreadyAppliedProject()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();

        // 認証状態にする
        $this->actingAs($user);

        // 既に応募済みとしてApplyテーブルにレコードを追加
        Apply::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // プロジェクト応募リクエストを送信
        $response = $this->post('/project/'. $project->id . '/'. $user->id. '/apply');

        // レスポンスのステータスコードを確認
        $response->assertStatus(302); // リダイレクト

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHas('flash_message', 'この案件には既に応募しています');
        $response->assertSessionHas('flash_message_type', 'error');
    }


}
