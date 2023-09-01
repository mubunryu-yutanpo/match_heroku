<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class ProjectController extends Controller
{
    /* ================================================================
        案件登録画面へ
    =================================================================*/
    public function new(){
        $user = Auth::user();

        return view('project/new', compact('user'));
    }

    /* ================================================================
        案件詳細画面へ
    =================================================================*/
    public function detail($project_id){
        $user = Auth::user();
        $project = $project_id;

        return view('project/detail', compact('user', 'project'));
    }

    /* ================================================================
        案件の編集・削除画面へ
    =================================================================*/
    public function edit($project_id){
        $user = Auth::user();
        $project = $project_id;

        return view('project/edit', compact('user', 'project'));
    }


}
