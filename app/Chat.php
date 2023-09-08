<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //カラムに挿入するものを指定
    protected $fillable = ['user1_id', 'user2_id'];

    //他のモデルとの関係
    public function me(){
        return $this->belongsTo('App\User');
    }

    public function you(){
        return $this->belongsTo('App\User');
    }

    public function message(){
        return $this->hasMany('App\DirectMessage');
    }

}
