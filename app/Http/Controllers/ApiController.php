<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\PublicMessage;

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

}
