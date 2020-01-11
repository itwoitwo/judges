<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['content', 'send_id','receive_id','judge'];

    public function user()
    {
        return $this->belongsTo(User::class,'receive_id');
    }
    
    public function voted_users()
    {
        return $this->belongsToMany(User::class, 'post_votes', 'post_id', 'user_id')->withtimestamps();
    }
}
