<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Post_vote;

class Post_votesController extends Controller
{
        public function agree(Request $request)
    {
        $userInfo = session()->get('userInfo');
        $user = User::find($userInfo['id_str']);
        
        //投票 
        $user->agree($request->id);
        
        $post = Post::find($request->id);
        $count_voted = $post->voted_users()->count();
        $vote_limit = config('services.vote.vote_limit');
        
        if($count_voted >= $vote_limit){
            $agree_rate = config('services.vote.agree_rate');
            $votes = Post_vote::query();
            $count_agree = $votes->where('post_id', $request->id)->where('vote', 'agree')->count();
            
            if($count_agree / $count_voted * 100 > $agree_rate){
                $post->judge = 'good';
                $post->save();
            } else {
                $post->judge = 'bad';
                $post->save();
            }
        
        }
        
        return back();
    }
    
    public function disagree(Request $request)
    {
        $userInfo = session()->get('userInfo');
        $user = User::find($userInfo['id_str']);
        
        //投票
        $user->disagree($request->id);
         
        $post = Post::find($request->id);
        $count_voted = $post->voted_users()->count();
        
        $votes = Post_vote::query();
        $count_agree = $votes->where('post_id', $request->id)->where('vote', 'agree')->count();
        $vote_limit = config('services.vote.vote_limit'); 
        
        if($count_voted >= $vote_limit){
            $agree_rate = config('services.vote.agree_rate');
            $votes = Post_vote::query();
            $count_agree = $votes->where('post_id', $request->id)->where('vote', 'agree')->count();
            
            if($count_agree / $count_voted * 100 > $agree_rate){
                $post->judge = 'good';
                $post->save();
            } else {
                $post->judge = 'bad';
                $post->save();
            }
        
        }        
        
        return back();
    }    
}
