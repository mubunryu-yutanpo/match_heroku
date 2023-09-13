<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Project;
use App\Chat;

class MessageController extends Controller
{

    /* ================================================================
        DM画面へ
    =================================================================*/
    public function directMessages($auth_user_id, $user_id){

        // チャットが存在するか確認
        $chat = Chat::where(function ($query) use ($auth_user_id, $user_id) {
            $query->where('user1_id', $auth_user_id)->where('user2_id', $user_id);
        })->orWhere(function ($query) use ($auth_user_id, $user_id) {
            $query->where('user1_id', $user_id)->where('user2_id', $auth_user_id);
        })->first();

        // 存在しない場合は新規に作成
        if (!$chat) {

            $chat = new Chat();
            $chat->fill([
                'user1_id' => $auth_user_id,
                'user2_id' => $user_id,
            ])->save();

        }

        $user = User::find($auth_user_id);

        return view('mypage/directMessage', compact('user', 'chat'));
    }
}
