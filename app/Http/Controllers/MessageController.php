<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Project;

class MessageController extends Controller
{
    /* ================================================================
        パブリックメッセージ画面へ
    =================================================================*/
    public function publicMessage($id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        $user_id = Auth::id();

        // 案件情報
        $project = Project::find($id)->with('user')->first();

        // 出品者のID
        $seller_id = Project::where('id', $id)->value('user_id');


        return view('/project/publicMessage', compact('project', 'user_id', 'seller_id'));

    }


    /* ================================================================
        DM画面へ
    =================================================================*/
    public function directMessages($id){

        $user = User::find($id);
        return view('mypage/message', compact('user'));
    }

}
