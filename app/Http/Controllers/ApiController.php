<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Project;
use App\PublicMessage;
use Illuminate\Database\QueryException;

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

        $messages = PublicMessage::where('project_id', $id)->with('user')->get();

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

}
