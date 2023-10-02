<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Project;
use App\Apply;
use App\Chat;



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

    /* ================================================================
        各ユーザーのページへ
    =================================================================*/
    public function userInfo($user_id){
        if (!ctype_digit($user_id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{

            // ユーザー情報
            $user = User::find($user_id);
            // 案件投稿数
            $postCount = Project::where('user_id', $user_id)->count();
            // 応募数
            $applyCount = Apply::where('user_id', $user_id)->count();

            return view('mypage/userInfo', compact('user', 'postCount', 'applyCount'));

        }catch(QueryException $e){
            Log::error('メソッド"userInfo"実行エラー：'. $e->getMessage());
            return redirect()->back()->with('flash_message', '予想外のエラーが発生しました')->with('flash_message_type', 'error');
        }
    }

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
