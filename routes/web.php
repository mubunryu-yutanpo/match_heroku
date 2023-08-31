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
    Route::get('/prof/{id}', 'MypageController@prof')->name('prof');
    Route::get('/withdrow/{id}', 'MypageController@withdrow')->name('withdrow');
    Route::get('/messages/{user_id}', 'MypageController@directMessages')->name('d_message');

    // 案件関連
    Route::get('/new', 'ProjectController@new')->name('new');
    Route::get('/project/{project_id}/detail', 'ProjectController@detail')->name('detail');
    Route::get('/edit/project/{project_id}', 'ProjectController@edit')->name('project.edit');

    /* ================================================================
        処理関連
    =================================================================*/

    /* ================================================================
        API関連
    =================================================================*/

    /* ================================================================
        ログアウト
    =================================================================*/
    
    Route::get('/logout', 'HomeController@logout')->name('logout');

    });
});

