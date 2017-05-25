<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    
    //
    // フォロー
    //
    
    // 自分がフォローしている人を取得
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    // 自分をフォローしてくれている人を取得
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    // フォロー
    public function follow($userId)
    {
        $exist  = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    // フォロー解除
    public function unfollow($userId)
    {
        $exist  = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->lists('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    //
    // お気に入り
    // 
    
    // お気に入り登録した投稿を取得
    public function favorite_microposts()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'favorite_id')->withTimestamps();
    }
    
    // お気に入り追加
    public function add_favorite($micropostId)
    {
        $exist  = $this->has_added_favorite($micropostId);
        if ($exist) {
            return false;
        } else {
            $this->favorite_microposts()->attach($micropostId);
            return true;
        }
    }
    // お気に入り解除
    public function remove_favorite($micropostId)
    {
        $exist  = $this->has_added_favorite($micropostId);
        if ($exist) {
            $this->favorite_microposts()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
    // お気に入り登録済みかどうか
    public function has_added_favorite($micropostId) {
        return $this->favorite_microposts()->where('favorite_id', $micropostId)->exists();
    }
    
}
