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
        $nonExistentUserId = 999;

        // APIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/profile/' . $nonExistentUserId);

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

    // 異常系
    public function testGetUserAvatarError1()
    {
        // テスト用のユーザーとプロジェクトを作成
        $user = factory(User::class)->create();
        $this->actingAs($user);


        // 間違ったAPIエンドポイントを呼び出し
        $response = $this->json('GET', '/api/' . $user->id . '/avatar');

        // エラーレスポンスを検証
        $response->assertStatus(200);

        // レスポンスの内容を検証
        $response->assertJson([
            'avatar' => 'avatar-default.png',
        ]);
    }


}
