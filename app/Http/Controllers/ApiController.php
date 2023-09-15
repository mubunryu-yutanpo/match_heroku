<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Project;
use App\PublicMessage;
use App\DirectMessage;
use App\Notification;
use App\Chat;
use App\Apply;

class ApiController extends Controller
{

    /* ================================================================
        プロフィール情報取得
    =================================================================*/
    public function getProfile($id){

        try{
            $user = User::findOrFail($id);

            $data = [
                'user' => $user,
            ];
    
            return response()->json($data);
    

        } catch (ModelNotFoundException $e) {
            // ユーザーデータが存在しない場合
            return response()->json([
                'flash_message' => 'ユーザーが見つかりませんでした',
                'flash_message_type' => 'error',
            ]);

        }catch(QueryException $e){
            // エラー時
            Log::error('プロフ情報取得エラー:'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
    }

    /* ================================================================
        アバター情報の取得
    =================================================================*/
    public function getAvatar($user_id){

        try{

            $avatar = User::where('id', $user_id)->value('avatar');

            $data = [
                'avatar' => $avatar,
            ];
    
            return response()->json($data);
    

        }catch(QueryException $e){
            // エラー時
            Log::error('アバター情報取得エラー:'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
    }

    /* ================================================================
        サムネイル取得
    =================================================================*/
    public function getThumbnail($project_id){

        try{

            $thumbnail = Project::where('id', $project_id)->value('thumbnail');

            $data = [
                'thumbnail' => $thumbnail,
            ];
    
            return response()->json($data);
    

        }catch(QueryException $e){
            // エラー時
            Log::error('サムネイル情報取得エラー:'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
    }

    /* ================================================================
        マイページ情報の取得
    =================================================================*/
    public function getMypage($user_id){

        try{
            // ====== 投稿した案件 ======
            $posts = Project::where('user_id', $user_id)
                            ->with('type')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

            $postList = [];
            if($posts->isNotEmpty()){
                $postList = $posts;
            }

            // ====== 応募した案件 ======
            $applies = Apply::where('user_id', $user_id)
                            ->with(['project' => function ($query) {
                                $query->with('type')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5);
                            }])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

            $applyList = [];
            if($applies->isNotEmpty()){
                $applyList = $applies;
            }

            // ====== パブリックメッセ ======

            $publicMessages = PublicMessage::where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->limit(5) // 自分が投稿したパブリックメッセージを最新の5件取得
                ->get();

            $publicMessageList = [];
            if ($publicMessages->isNotEmpty()) {
                $publicMessageList = $publicMessages;
                
                // 各パブリックメッセージに紐づくプロジェクトを取得し、最新の1件のパブリックメッセージを追加
                foreach ($publicMessageList as $publicMessage) {
                    $project = Project::where('id', $publicMessage->project_id)
                        ->with(['type'])
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    $publicMessage->project = $project;
                }
            }
        
            // ====== DM ======
            // 自分が送信or受信したDM情報を、最新5件取得
            $dms = Chat::where('user1_id', $user_id)
                        ->orWhere('user2_id', $user_id)
                        ->with(['message' => function ($query) {
                            $query->with('user')->get();
                        }])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
        
            $directMessageList = [];
            if($dms->isNotEmpty()){
                $directMessageList = $dms;
            }

            $data = [
                'postList'          => $postList,
                'applyList'         => $applyList,
                'publicMessageList' => $publicMessageList,
                'directMessageList' => $directMessageList,
            ];

            return response()->json($data);

        }catch(QueryException $e){
            // エラー時
            Log::error('マイページ情報取得エラー：'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
    }

    /* ================================================================
        案件情報取得（一覧用）
    =================================================================*/
    public function getProjects(){

        try{
            $projects = Project::with('type')->get();

            $data = [
                'projects' => $projects,
            ];
    
            return response()->json($data);

        }catch(QueryException $e){
            // エラー時
            Log::error('一覧ページ・案件情報取得エラー：'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }

    }

    /* ================================================================
        案件情報取得(詳細ページ用）
    =================================================================*/
    public function getProjectDetail($id){

        try{

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

        }catch(QueryException $e){
            // エラー時
            Log::error('詳細ページ・案件情報取得エラー：'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
    }

    /* ================================================================
        案件に対するパブリックメッセ情報取得
    =================================================================*/
    public function getPublicMessages($id){

        try{

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
    

        }catch(QueryException $e){
            // エラー時
            Log::error('パブリックメッセージ情報取得エラー：'. $e->getMessage());
            return response()->json([
                'flash_message'      => 'エラーが発生しました',
                'flash_message_type' => 'error',
            ]);
        }
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
            
            return response()->json([
                'flash_message'      => '不正な操作が行われました',
                'flash_message_type' => 'error',
            ]);
        }

        try{

            // メッセージを保存
            $message = new PublicMessage;
            $saved = $message->fill([
                'user_id'    => $user_id,
                'project_id' => $project_id,
                'comment'    => $request->comment,
            ])->save();

            if($saved){

                // 成功時
                return response()->json([
                    'flash_message'      => 'メッセージを送信しました',
                    'flash_message_type' => 'success',
                ]);
    
            }else{

                // 失敗時
                return response()->json([
                    'flash_message'      => 'メッセージの送信に失敗しました',
                    'flash_message_type' => 'error',
                ]);
            }


        }catch(QueryException $e){
            Log::error('パブリックメッセ追加エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        DM情報取得
    =================================================================*/
    public function getDirectMessage($chat_id){

        try{
            $messages = DirectMessage::where('chat_id', $chat_id)->with('user')->get();

            $messageList = [];
            if($messages->isNotEmpty()){
                $messageList = $messages;
            }
    
            $data = [
                'messageList' => $messageList,
            ];
    
            return response()->json($data);

        }catch(QueryException $e){
            Log::error('DM情報取得エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
        }
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


    /* ================================================================
        投稿した案件情報取得（一覧用）
    =================================================================*/
    public function getPostList($user_id){

        try{

            $projects = Project::where('user_id', $user_id)->get();

            $projectList = [];
            if($projects->isNotEmpty()){
                $projectList = $projects;
            }

            $data = [
                'projectList' => $projectList,
            ];

            return response()->json($data);

        }catch(QueryException $e){
            Log::error('一覧用・投稿した案件取得エラー：'. $e->getMessage());

            return response()->json([
                'flashMessage' => '予想外のエラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }
    }

    /* ================================================================
        応募した案件情報取得（一覧用）
    =================================================================*/
    public function getApplyList($user_id){
    
        try{

            $applies = Apply::where('user_id', $user_id)->with('project')->get();

            $applyList = [];
            if($applies->isNotEmpty()){
                $applyList = $applies;
            }

            $data = [
                'applyList' => $applyList,
            ];

            return response()->json($data);

        }catch(QueryException $e){
            Log::error('一覧用・応募した案件情報取得エラー：'. $e->getMessage());

            return response()->json([
                'flashMessage' => '予想外のエラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }

    }

    /* ================================================================
        パブメッセ情報取得（一覧用）
    =================================================================*/
    public function getPublicMessageList($user_id){
    
        try{

            // 自分の案件用
            $project_id = Project::where('user_id', $user_id)->value('id');

            $publicMessages = PublicMessage::where('user_id', $user_id)
                                ->orWhere('project_id', $project_id)
                                ->with('project')
                                ->get();

            $publicMessageList = [];
            if($publicMessages->isNotEmpty()){
                $publicMessageList = $publicMessages;
            }

            $data = [
                'publicMessageList' => $publicMessageList,
            ];

            return response()->json($data);

        }catch(QueryException $e){
            Log::error('一覧用・パブリックメッセージ取得エラー：'. $e->getMessage());

            return response()->json([
                'flashMessage' => '予想外のエラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }

    }

    /* ================================================================
        DM情報取得（一覧用）
    =================================================================*/
    public function getDirectMessageList($user_id){
    
        try{

            // チャットごとに取得する。それ用のID
            $chat_id = Chat::where('user1_id', $user_id)
                            ->orWhere('user2_id', $user_id)
                            ->value('id');

            $directMessages = PublicMessage::where('chat_id', $chat_id)->get();

            $directMessageList = [];
            if($directMessages->isNotEmpty()){
                $directMessageList = $directMessages;
            }

            $data = [
                'publicMessageList' => $directMessageList,
            ];

            return response()->json($data);

        }catch(QueryException $e){
            Log::error('一覧用・DM取得エラー：'. $e->getMessage());

            return response()->json([
                'flashMessage' => '予想外のエラーが発生しました',
                'flashMessageType' => 'error',
            ]);
        }

    }



}
