<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;


class ApiController extends Controller
{

    /* ================================================================
        プロフィール情報取得
    =================================================================*/
    public function getProfile($id){

        $user = User::find($id);

        $data = [
            'user' => $user,
        ];

        return response()->json('user');
    }
}
