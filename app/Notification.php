<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //カラムに挿入するものを指定
    protected $fillable = ['receiver_id', 'sender_id', 'chat_id', 'read', 'content'];

    //他のモデルとの関係
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function chat(){
        return $this->belongsTo('App\Chat');
    }
    public function dm(){
        return $this->belongsTo('App\DirectMessage');
    }

}
