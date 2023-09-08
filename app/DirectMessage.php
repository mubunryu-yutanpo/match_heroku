<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectMessage extends Model
{
    // テーブル名を任意に指定
    protected $table = 'direct_messages';

    //カラムに挿入するものを指定
    protected $fillable = ['sender_id', 'chat_id', 'comment'];

    //他のモデルの関係
    public function user(){
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function chat(){
        return $this->belongsTo('App\Chat');
    }

}
