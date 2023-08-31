<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;


class MypageController extends Controller
{

    /* ================================================================
        マイページへ
    =================================================================*/
    public function mypage(){
        return view('mypage/mypage');
    }

    /* ================================================================
        プロフィール編集へ
    =================================================================*/
    public function prof($id){

        $user = User::find($id);
        return view('mypage/prof', compact('user'));
    }

    /* ================================================================
        退会画面へ
    =================================================================*/
    public function withdrow($id){

        $user = User::find($id);
        return view('mypage/withdrow', compact('user'));
    }

    /* ================================================================
        DM画面へ
    =================================================================*/
    public function directMessages($id){

        $user = User::find($id);
        return view('mypage/message', compact('user'));
    }

}
