<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'avatar',
        'name',
        'screen_name',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','password'
    ];
    
    public function posts()
    {
        return $this->hasMany(Post::class,'receive_id');
    }
    
    public function voted_posts()
    {
        return $this->belongsToMany(Post::class, 'post_votes', 'user_id', 'post_id')->withtimestamps();
    }
    
    public function agree($postId)
    {
        $exist = $this->is_voted($postId);
        
        if($exist){
            return false;
        } else {
            $this->voted_posts()->attach($postId);
            return true;
        }
    }
    
    public function disagree($postId)
    {
        $exist = $this->is_voted($postId);
        
        if($exist){
            return false;
        } else {
            $this->voted_posts()->attach($postId,['vote'=> 'disagree']);
            return true;
        }
    }    
    
    
    public function is_voted($postId)
    {
        return $this->voted_posts()->where('post_id', $postId)->exists();
    }
}
