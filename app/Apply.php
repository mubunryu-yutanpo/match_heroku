<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    // テーブル名を任意に指定
    protected $table = 'apply';

    //カラムに挿入するものを指定
    protected $fillable = ['user_id', 'project_id'];

    //他のモデルの関係
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }
}
