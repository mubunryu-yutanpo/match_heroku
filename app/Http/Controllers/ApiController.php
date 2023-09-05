<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;


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

}
