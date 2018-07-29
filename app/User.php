<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Micropost;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * 
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        //すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        //自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであれば、フォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        //既にフォローしているかの確認
        $exist = $this->is_following($userId);
        //自分自身でないかの確認
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            //既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば、何もしない
            return false;
        }
    }
    
    // Followしているユーザ全てを取得(中間テーブルから、follow_idと$userIdが一致するもの)
    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    // Followしているユーザと自分のMicroposts全てを取得
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    // 多対多の関係を定義
    public function saving_favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id', 'fav_id')->withTimestamps();

    }
    
    public function save_favorite($favId)
    {
        //既にお気に入り登録しているか確認
        $exist = $this->is_saving_fav($favId);
        
        if ($exist) {
            return false;
        } else {
            //未登録であれば登録する
            $this->saving_favorites()->attach($favId);
            return true;
        }
    }
    
    public function unsave_favorite($favId)
    {
        //既にお気に入り登録しているか確認
        $exist = $this->is_saving_fav($favId);
        
        if ($exist) {
            // 登録済みであれば、登録を外す
            $this->saving_favorites()->detach($favId);
            return true;
        } else {
            return false;
        }
    }
    
    // 
    public function is_saving_fav($favId){
        return $this->saving_favorites()->where('fav_id', $favId)->exists();
    }
    
 
}
