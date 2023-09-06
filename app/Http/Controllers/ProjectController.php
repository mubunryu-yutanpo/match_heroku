<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Type;
use App\Project;
use Illuminate\Database\QueryException;

class ProjectController extends Controller
{
    /* ================================================================
        案件登録画面へ
    =================================================================*/
    public function new(){
        $user = Auth::user();
        $projectType = Type::all();

        return view('project/new', compact('user', 'projectType'));
    }

    /* ================================================================
        案件の新規登録
    =================================================================*/
    public function create(Request $request){

        $user_id = Auth::id();

        try{
            $project = new Project;

            $saved = $project->fill([
                'user_id'    => $user_id,
                'title'      => $request->title,
                'type'       => $request->type,
                'upperPrice' => $request->upperPrice,
                'lowerPrice' => $request->lowerPrice,
                'content'    => $request->content,
            ])->save();

            if($saved){
                // 成功時
                return redirect('/mypage')->with('flash_message', '案件を投稿しました！')->with('flash_message_type', 'success');
            }else{
                // 失敗時
                return redirect('/mypage')->with('flash_message', 'データの保存に失敗しました。')->with('flash_message_type', 'error');
            }

        }catch(QueryException $e){
            // エラー内容をログに吐いてリダイレクト
            Log::error('新規案件登録処理エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }


    /* ================================================================
        案件詳細画面へ
    =================================================================*/
    public function detail($id){
        $user = Auth::user();
        $project_id = $id;

        return view('project/detail', compact('user', 'project_id'));
    }

    /* ================================================================
        案件の編集・削除画面へ
    =================================================================*/
    public function edit($project_id){
        $user = Auth::user();

        $project = Project::find($project_id);
        $projectType = Type::all();


        return view('project/edit', compact('user', 'project', 'projectType'));
    }

    /* ================================================================
        案件の更新処理
    =================================================================*/
    public function projectUpdate(Request $request, $id){
                
        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{
            $project = Project::find($id);
            $user_id = Auth::id();

            // ユーザーのチェック(違う場合はリダイレクト)
            if(!$user_id === $project->user_id){
                redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
            }

            $updated = $project->update([
                'user_id'    => $user_id,
                'title'      => $request->title,
                'type'       => $request->type,
                'upperPrice' => $request->upperPrice,
                'lowerPrice' => $request->lowerPrice,
                'content'    => $request->content,
            ]);

            if($updated){
                // 成功時
                return redirect('/mypage')->with('flash_message', '情報を更新しました！')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect('/mypage')->with('flash_message', 'データの更新に失敗しました')->with('flash_message_type', 'error');
            }
            
        }catch(QueryException $e){
            // エラーをログに吐いてリダイレクト
            Log::error('案件更新処理エラー：'. $e->getMessage());
            redirect('/')->with('flash_message', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }
    }

    /* ================================================================
        案件の削除処理
    =================================================================*/
    public function projectDelete($id){

        if (!ctype_digit($id)) {
            return redirect('/')->with('flash_message', '不正な操作が行われました')->with('flash_message_type', 'error');
        }

        try{

            $project = Project::find($id);

            // ユーザーのチェック
            $auth_user_id = Auth::id();

            if(!$auth_user_id === $project->user_id){
                redirect('/')->with('flash_message', 'エラーが発生しました')->with('flash_message_type', 'error');
            }

            // データを削除
            $delete = $project->delete();

            if($delete){
                // 成功時
                return redirect('/mypage')->with('flash_message', '削除しました！')->with('flash_message_type', 'success');

            }else{
                // 失敗時
                return redirect('/')->with('flash_message', 'エラーが発生しました。')->with('flash_message_type', 'error');
            }


        }catch(QueryException $e){
            // エラーをログに吐いてリダイレクト
            Log::error('案件削除処理エラー：'. $e->getMessage());
            return redirect('/')->with('flash_message_type', '予想外のエラーが発生しました。')->with('flash_message_type', 'error');
        }

    }

}
