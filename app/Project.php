<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //カラムに挿入するものを指定
    protected $fillable = ['user_id', 'title', 'upperPrice', 'lowerPrice', 'thumbnail', 'type', 'content'];

    //他のモデルの関係
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function type(){
        return $this->belongsTo('App\Type', 'type');
    }

    public function publicMessages()
{
    return $this->hasMany(PublicMessage::class);
}
}
