<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //カラムに挿入するものを指定
    protected $fillable = ['user1_id', 'user2_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function message(){
        return $this->hasMany('App\DirectMessage');
    }

}
