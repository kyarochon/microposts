<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // お気に入り登録してくれたユーザ
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'favorite_id', 'user_id')->withTimestamps();
    }
}