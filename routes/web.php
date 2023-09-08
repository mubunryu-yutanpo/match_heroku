<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ================================================================

    全ユーザーが閲覧可能ページ

=================================================================*/

Route::get('/', 'HomeController@home')->name('home');
Route::get('/list', 'HomeController@projectList')->name('list');


/* ================================================================

    認証関連

=================================================================*/

// Auth::routes();
Auth::routes(['verify' => true]);

Route::middleware('verified')->group(function () {
    Route::group(['middleware' => 'auth'], function () {

    /* ================================================================
        View関連(認証あり)
    =================================================================*/

    // マイページ関連
    Route::get('/mypage', 'MypageController@mypage')->name('mypage');
    Route::get('/prof/{user_id}', 'MypageController@prof')->name('prof');
    Route::get('/withdraw/{user_id}', 'MypageController@withdraw')->name('withdraw');

    // 案件関連
    Route::get('/new', 'ProjectController@new')->name('new');
    Route::get('/project/{project_id}/detail', 'ProjectController@detail')->name('detail');
    Route::get('/edit/project/{project_id}', 'ProjectController@edit')->name('project.edit');

    // メッセージ関連
    Route::get('/messages/{auth_user_id}/{user_id}', 'MessageController@directMessages')->name('d.message');
    Route::get('/project/{project_id}/messages', 'MessageController@publicMessage')->name('pub.message');


    /* ================================================================
        処理関連
    =================================================================*/

    // マイページ関連

    Route::post('/prof/{user_id}/edit', 'MypageController@profUpdate')->name('prof.update');
    Route::post('/withdraw/{user_id}/destroy', 'MypageController@destroy')->name('destroy');

    // 案件関連

    Route::post('/new', 'ProjectController@create')->name('create');
    Route::post('/edit/project/{project_id}/update', 'ProjectController@projectUpdate')->name('project.update');
    Route::post('/edit/project/{project_id}/delete', 'ProjectController@projectDelete')->name('project.delete');

    // メッセージ関連

    /* ================================================================
        ログアウト
    =================================================================*/
    
    Route::get('/logout', 'HomeController@logout')->name('logout');

    });
});

/* ================================================================

    API関連

=================================================================*/

Route::middleware('api')->group(function(){

    // プロフィール取得
    Route::get('/api/profile/{user_id}', 'ApiController@getProfile');
    // 案件一覧
    Route::get('/api/projects', 'ApiController@getProjects');
    // 案件詳細
    Route::get('/api/{project_id}/detail', 'ApiController@getProjectDetail');
    // 案件のパブリックメッセージ取得
    Route::get('/api/{project_id}/publicMessages', 'ApiController@getPublicMessages');
    // パブリックメッセージ追加
    Route::post('/api/project/{project_id}/{user_id}/publicMessage', 'ApiController@postPublicMessage');
    // ダイレクトメッセージの取得
    Route::get('/api/messages/{chat_id}', 'ApiController@getDirectMessage');
    // ダイレクトメッセージ追加
    Route::post('/api/message/{user_id}/{chat_id}', 'ApiController@sendMessage');


});

