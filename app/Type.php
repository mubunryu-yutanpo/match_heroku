<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    // テーブル名を任意に指定
    protected $table = 'project_type';

    //カラムに挿入するものを指定
    protected $fillable = ['name'];

    //他のモデルの関係
    public function project(){
        return $this->hasMany('App\Project');
    }

}
