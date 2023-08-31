<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;



class HomeController extends Controller
{
    /* ================================================================
        ホーム
    =================================================================*/
    public function home(){
        return view('welcome');
    }

    /* ================================================================
        ログアウト
    =================================================================*/
    public function logout(){
        Auth::logout();
        return redirect('/')->with('flash_message', 'ログアウトしました')->with('flash_message_type', 'success');
    }

    /* ================================================================
        ログイン状態チェック
    =================================================================*/
    public function loginCheck(){
        $auth = Auth::check();
        $data = [
            'auth' => $auth,
        ];
        return response()->json($data);
    }

    /* ================================================================
        案件一覧ページへ
    =================================================================*/
    public function projectList(){
        return view('project/list');
    }

}
