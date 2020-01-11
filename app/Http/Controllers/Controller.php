<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function counts($post) {
        $count_voted = $post->voted_users()->count();

        return [
            'count_voted' => $count_voted,
        ];
    }    
}
