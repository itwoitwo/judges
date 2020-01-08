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
}
