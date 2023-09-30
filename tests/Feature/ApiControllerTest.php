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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception; 
use App\User;
use App\Apply;
use App\Project;
use App\Chat;
use App\DirectMessage;
use App\PublicMessage;
use App\Type;
use App\Notification;

class ApiControllerTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;


    /* ================================================================
        メソッド名：getProfile
    =================================================================*/
    public function testGetProfile()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/profile/' . $user->id);

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJson(['user' => $user->toArray()]);
    }

    // 異常系
    public function testGetProfileError1()
    {
        // 存在しないユーザーIDを指定
        $notExistUserId = 999;

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/profile/' . $notExistUserId);

        // エラーレスポンスを検証
        $response->assertStatus(200)
                    ->assertJson([
                        'flash_message' => 'ユーザーが見つかりませんでした',
                        'flash_message_type' => 'error',
                    ]);
    }


    /* ================================================================
        メソッド名：getAvatar
    =================================================================*/
    public function testGetAvatar()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id . '/avatar/');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'avatar',
                    ]);
            
    }


    /* ================================================================
        メソッド名：getThumbnail
    =================================================================*/
    public function testGetThumbnail()
    {
        // テスト用のプロジェクトを作成
        $project = factory(Project::class)->create();

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $project->id . '/thumbnail/');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJson(['thumbnail' => $project->thumbnail]);
    }


    /* ================================================================
        メソッド名：getMypage
    =================================================================*/
    public function testGetMyPage()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // プロジェクトを作成し、ユーザーに紐付ける
        $projects = factory(Project::class, 5)->create(['user_id' => $user->id]);

        // 応募を作成し、ユーザーに紐付ける
        $applies = factory(Apply::class, 5)->create(['user_id' => $user->id]);

        // パブリックメッセージを作成し、ユーザーに紐付ける
        $publicMessages = factory(PublicMessage::class, 5)->create(['user_id' => $user->id]);

        // ダイレクトメッセージを作成し、ユーザーに紐付ける
        $directMessages = factory(DirectMessage::class, 5)->create(['sender_id' => $user->id]);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id . '/mypage/');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'postList',
                        'applyList',
                        'publicMessageList',
                        'directMessageList',
                    ]);
    }

    // 異常系
    public function testGetMyPageError1()
    {
        // 存在しないユーザーIDを指定
        $notExistUserId = 999;

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $notExistUserId . '/mypage/');

        // ステータスコードを検証
        $response->assertStatus(200);

        // レスポンスの内容を検証
        $response->assertJson([
            'postList' => NULL,
            'applyList' => NULL,
            'publicMessageList' => NULL,
            'directMessageList' => NULL,
        ]);
    }


    /* ================================================================
        メソッド名：getProject
    =================================================================*/
    public function testGetProjects()
    {
        // テスト用のプロジェクトを作成
        $projects = factory(Project::class, 5)->create();

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/projects');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'projects',
                    ]);
    }

    /* ================================================================
        メソッド名：getProjectDetail
    =================================================================*/
    public function testGetProjectDetail()
    {
        // テスト用のプロジェクトを作成
        $project = factory(Project::class)->create();

        // 関連するメッセージを作成
        $messages = factory(PublicMessage::class, 5)->create(['project_id' => $project->id]);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $project->id. '/detail/');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'project',
                        'messageList',
                    ]);
    }

    // 異常系
    public function testGetProjectDetailError1()
    {
        // 存在しないプロジェクトIDを指定
        $nonExistentProjectId = 999;

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $nonExistentProjectId. '/detail/');

        // ステータスコードを検証
        $response->assertStatus(200);

        // レスポンスの内容を検証
        $response->assertJson([
            'project' => NULL,
            'messageList' => NULL,
        ]);
    }


    /* ================================================================
        メソッド名：getPublicMessages
    =================================================================*/
    public function testGetPublicMessagesSuccess()
    {
        // テスト用のプロジェクトを作成
        $project = factory(Project::class)->create();

        // 関連する公開メッセージを作成
        $messages = factory(PublicMessage::class, 5)->create(['project_id' => $project->id]);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/'. $project->id. '/publicMessages/');

        // レスポンスを検証
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'messageList',
                        'seller_id',
                    ]);
    }

    // 異常系
    public function testGetPublicMessagesError1()
    {
        // 存在しないプロジェクトIDを指定
        $notExistProjectId = 999;

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/'. $notExistProjectId. 'publicMessages/');

        // ステータスコードを検証
        $response->assertStatus(404);
    }

    /* ================================================================
        メソッド名：postPublicMessage
    =================================================================*/
    public function testPostPublicMessage()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create();

        // ログイン
        $this->actingAs($user);

        // メッセージデータを生成
        $comment = $this->faker->sentence;

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // APIエンドポイントを呼び出し
        $response = $this->json('POST', '/api/project/'. $project->id .  '/' . $user->id. '/publicMessage', [
            'comment' => $comment,
        ]);

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJson([
                'flash_message_type' => 'success',
            ]);
    }

    // 異常系
    public function testErrorHandlingWhenUserIdDoesNotMatchAuthenticatedUser()
    {
        // ダミーのプロジェクトIDとユーザーIDを生成
        $project_id = $this->faker->randomNumber();
        $user = factory(User::class)->create();

        // 不一致のユーザーIDを返す
        $user_id = $user->id + 10;

        // ログイン
        $this->actingAs($user);

        // メッセージデータを生成
        $comment = $this->faker->sentence;

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // APIエンドポイントを呼び出し
        $response = $this->json('POST', '/api/project/'. $project_id .  '/' . $user_id. '/publicMessage', [
            'comment' => $comment,
        ]);

        // エラーレスポンスを検証
        $response->assertStatus(200)
            ->assertJson([
                'flash_message_type' => 'error',
            ]);
    }

    /* ================================================================
        メソッド名：getDirectMessage
    =================================================================*/
    public function testDirectMessages()
    {
        // チャットを生成
        $chat = factory(Chat::class)->create();
        $chat_id = $chat->id;

        // テスト用のダイレクトメッセージを作成
        factory(DirectMessage::class, 5)->create(['chat_id' => $chat_id]);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/messages/' . $chat_id);

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'messageList',
            ]);
    }


    /* ================================================================
        メソッド名：sendMessage
    =================================================================*/
    public function testSendMessage()
    {
        // テスト用のダイレクトメッセージを作成
        $chat = factory(Chat::class)->create();
        $user_id = $chat->user1_id; // メッセージを送信するユーザーID
        $chat_id = $chat->id;
        $comment = 'テストメッセージ';

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // APIエンドポイントを呼び出し
        $response = $this->json('POST', '/api/message/' . $user_id . '/' . $chat_id, [
            'comment' => $comment,
        ]);

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'flashMessage',
                'flashMessageType',
            ]);
    }

    // 異常系
    public function testSendMessageError1(){
        
        // テスト用のダイレクトメッセージを作成
        $chat = factory(Chat::class)->create();
        $notExistUserId = 999; // 関係のないユーザーのID
        $chat_id = $chat->id;
        $comment = 'ユーザーが違うパターン';

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // APIエンドポイントを呼び出し
        $response = $this->json('POST', '/api/message/' . $notExistUserId . '/' . $chat_id, [
            'comment' => $comment,
        ]);

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJson([
                'flashMessage' => 'エラーが発生しました',
                'flashMessageType' => 'error',
            ]);

    }

    /* ================================================================
        メソッド名：getPostList
    =================================================================*/
    public function testGetPostList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テスト用のプロジェクトを作成し、ユーザーに紐づける
        $projects = factory(Project::class, 3)->create(['user_id' => $user->id]);

        // ログイン
        $this->actingAs($user);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id. '/postList/');

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'projectList',
            ])
            ->assertJsonCount(3, 'projectList');
    }


    /* ================================================================
        メソッド名：getApplyList
    =================================================================*/
    public function testGetApplyList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テスト用の応募情報を作成し、ユーザーに紐づける
        $applies = factory(Apply::class, 3)->create(['user_id' => $user->id]);

        // ログイン
        $this->actingAs($user);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id . '/applyList/');

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'applyList',
            ])
            ->assertJsonCount(3, 'applyList');
    }

    /* ================================================================
        メソッド名：getPublicMessageList
    =================================================================*/
    public function testGetPublicMessageList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テスト用のパブリックメッセージを作成し、ユーザーに紐づける
        $publicMessages = factory(PublicMessage::class, 3)->create(['user_id' => $user->id]);

        // ログイン
        $this->actingAs($user);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id. '/publicMessageList/');

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'publicMessageList',
            ])
            ->assertJsonCount(3, 'publicMessageList');
    }

    /* ================================================================
        メソッド名：getDirectMessageList
    =================================================================*/
    public function testGetDirectMessageList()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テスト用のチャットとメッセージを作成
        $chat1 = factory(Chat::class)->create(['user1_id' => $user->id]);
        $message1 = factory(DirectMessage::class)->create(['chat_id' => $chat1->id]);
        
        $chat2 = factory(Chat::class)->create(['user2_id' => $user->id]);
        $message2 = factory(DirectMessage::class)->create(['chat_id' => $chat2->id]);

        // ログイン
        $this->actingAs($user);

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id. '/directMessageList/');

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJsonStructure([
                'directMessageList',
            ])
            ->assertJsonCount(2, 'directMessageList');
    }


    /* ================================================================
        メソッド名：markAsRead
    =================================================================*/
    public function testMarkAsRead()
    {
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テスト用のチャットと通知を作成
        $chat = factory(Chat::class)->create();
        $notification = factory(Notification::class)->create([
            'chat_id' => $chat->id,
            'sender_id' => $chat->user1_id, // 通知がユーザー1からのもの
            'receiver_id' => $chat->user2_id,
            'read' => false,
        ]);

        // ログイン
        $this->actingAs($user);

        // CSRFトークンの検証を一時的に無効化
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // APIエンドポイントを呼び出し
        $response = $this->json('POST', '/api/markAsRead/' . $chat->id . '/' . $chat->user1_id . '/' . $chat->user2_id);

        // レスポンスを検証
        $response->assertStatus(200)
            ->assertJson([
                'flashMessageType' => 'success',
            ]);

        // 通知が既読になっていることを確認
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'read' => true,
        ]);
    }

}