<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use App\User;
use App\Project;
use App\PublicMessage;
use App\DirectMessage;
use App\Notification;
use App\Chat;

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

        return response()->json($data);
    }

    /* ================================================================
        アバター情報の取得
    =================================================================*/
    public function getAvatar($user_id){
        $avatar = User::where('id', $user_id)->value('avatar');

        $data = [
            'avatar' => $avatar,
        ];

        return response()->json($data);
    }


    /* ================================================================
        案件情報取得（一覧用）
    =================================================================*/
    public function getProjects(){

        $projects = Project::with('type')->get();

        $data = [
            'projects' => $projects,
        ];

        return response()->json($data);

    }

    /* ================================================================
        案件情報取得(詳細ページ用）
    =================================================================*/
    public function getProjectDetail($id){

        // === 案件情報 ===
        $project = Project::where('id', $id)->with('user', 'type')->first();

        // === 案件に対するメッセージ（パブリックメッセージ）===
        $messageList = [];
        // 最新の10件取得
        $messages = PublicMessage::where('project_id', $id)
                                ->with('user')
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        if($messages->isNotEmpty()){
            $messageList = $messages;
        }

        $data = [
            'project'    => $project,
            'messageList' => $messageList,
        ];

        return response()->json($data);
    }

    /* ================================================================
        案件に対するパブリックメッセ情報取得
    =================================================================*/
    public function getPublicMessages($id){

        $seller_id = Project::where('id', $id)->value('user_id');
        $messages = PublicMessage::where('project_id', $id)->with('user')->get();

        $messageList = [];
        if($messages->isNotEmpty()){
            $messageList = $messages;
        }

        $data = [
            'messageList' => $messageList,
            'seller_id'   => $seller_id,
        ];

        return response()->json($data);
    }

    /* ================================================================
        パブリックメッセージ新規追加
    =================================================================*/
    public function postPublicMessage(Request $request, $project_id, $user_id){

        // キャスト
        $project_id = (int)$project_id;
        $user_id    = (int)$user_id;

        // ユーザーのチェック
        if($user_id != Auth::id() ){
            return redirect('/')->with('flash_message', '不正な操作です')->with('flash_message_type', 'error');
        }

        try{

            // メッセージを保存
            $message = new PublicMessage;
            $saved = $message->fill([
                'user_id'    => $user_id,
                'project_id' => $project_id,
                'comment'    => $request->comment,
            ])->save();


        }catch(QueryException $e){
            Log::error('パブリックメッセ追加エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        DM情報取得
    =================================================================*/
    public function getDirectMessage($chat_id){

        $messages = DirectMessage::where('chat_id', $chat_id)->with('user')->get();

        $messageList = [];
        if($messages->isNotEmpty()){
            $messageList = $messages;
        }

        $data = [
            'messageList' => $messageList,
        ];

        return response()->json($data);
    }

    /* ================================================================
        DM新規追加
    =================================================================*/
    public function sendMessage(Request $request , $user_id, $chat_id){

        // チャットに紐づけられているユーザーかチェック
        $chat = Chat::find($chat_id);
        $user_id = (int)$user_id;

        if($user_id !== $chat->user1_id && $user_id !== $chat->user2_id){

            // ユーザーが違う場合
            return response()->json([
                'flashMessage' => 'エラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }

        try{
            // DM情報を保存
            $dm = new DirectMessage;
            $dmSaved = $dm->fill([
                'sender_id' => $user_id,
                'chat_id'   => $chat_id,
                'comment'   => $request->comment,
            ])->save();

            // 通知情報を保存
            $notification = new Notification;

            // チャットに紐づいているユーザーのうち、送信者じゃない方のIDを設定
            $receiver_id = ($user_id === $chat->user1_id) ? $chat->user2_id : $chat->user1_id;

            $notifiSaved = $notification->fill([
                'receiver_id'  => $receiver_id,
                'sender_id'    => $user_id,
                'chat_id'      => $chat_id,
                'read'         => false,
                'content'      => '新しいメッセージがあります',
            ])->save();

            // DM追加処理の判定
            if($dmSaved && $notifiSaved){
                // 成功時
                return response()->json([
                    'flashMessage' => 'メッセージを送信しました！',
                    'flashMessageType' => 'success',
                ]);
    
            }else{
                // 失敗時
                return response()->json([
                    'flashMessage' => 'メッセージの保存に失敗しました',
                    'flashMessageType' => 'error',
                ]);    
            }

        }catch(QueryException $e){
            Log::error('DM追加処理エラー：'. $e->getMessage());

            return response()->json([
                'flashMessage' => '予想外のエラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }
    }

}
