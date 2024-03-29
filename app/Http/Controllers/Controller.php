<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function counts($user) {
        // 投稿数
        $count_microposts = $user->microposts()->count();
        // フォロー／フォロワー数
        $count_followings = $user->followings()->count();
        $count_followers  = $user->followers()->count();
        // お気に入り数
        $count_favorites = $user->favorite_microposts()->count();

        return [
            'count_microposts' => $count_microposts,
            'count_followings' => $count_followings,
            'count_followers' => $count_followers,
            'count_favorites' => $count_favorites,
        ];
    }
    
}
