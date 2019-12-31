<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Input;

class OAuthController extends Controller
{
    public $consumerKey = '';
    public $consumerSecret ='';
    public $callBackUrl ='';
    
    
    public function __construct()
    {
        $this->consumerKey = config('services.twitter.APIkey');
        $this->consumerSecret = config('services.twitter.APIsecret');
        $this->callBackUrl = config('services.twitter.callBackUrl');
    }
    
    
    public function login(){
       $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
       
        // リクエストトークン取得
        $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => $this->callBackUrl));
        //　認証用URL取得
        $url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        
        return redirect($url);
    }
    
    public function callBack()
    {
        //GETパラメータから認証トークン取得
        $oauth_token = Input::get('oauth_token');
        //GETパラメータから認証キー取得
        $oauth_verifier = Input::get('oauth_verifier');
    
        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            $this->consumerKey,
            //API Secret
            $this->consumerSecret,
            //認証トークン
            $oauth_token,
            //認証キー
            $oauth_verifier
        );
    
        //アクセストークン取得
        //'oauth/access_token'はアクセストークンを取得するためのAPIのリソース
        $accessToken = $twitter->oauth('oauth/access_token', array('oauth_token' => $oauth_token, 'oauth_verifier' => $oauth_verifier));
    
        //セッションにアクセストークンを登録
        session()->put('accessToken', $accessToken);
    
        //indexページにリダイレクト
        return redirect('/');
    }
}
