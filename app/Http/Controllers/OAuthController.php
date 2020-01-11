<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

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
    
    
    public function OAuthlogin(){
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
        $connection = new TwitterOAuth(
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
        $accessToken = $connection->oauth('oauth/access_token', array('oauth_token' => $oauth_token, 'oauth_verifier' => $oauth_verifier));
        
        
        $twitter = new TwitterOAuth(
        //API Key
        $this->consumerKey,
        //API Secret
        $this->consumerSecret,
        //アクセストークン
        $accessToken['oauth_token'],
        $accessToken['oauth_token_secret']
        );
        
        //ユーザー情報の取得
        $userInfo = get_object_vars($twitter->get('account/verify_credentials'));
        $user = User::find($userInfo['id_str']);
        
        //セッションにアクセストークン、ユーザー情報を登録
        session()->put('accessToken', $accessToken);
        session()->put('userInfo', $userInfo);
        
        if($user){
            if($user->screen_name !== $userInfo['screen_name']){
                $user->screen_name = $userInfo['screen_name'];
                $user->save();
            }
        } else {
        //ユーザー登録
        User::create([
            'id' => $userInfo['id_str'],
            'avatar' => $userInfo['profile_image_url'],
            'name' => $userInfo['name'],
            'screen_name' => $userInfo['screen_name'],
            'password' => bcrypt($accessToken['oauth_token']),
            ]);
        }
                return redirect('index');
    }
    
    public function index()
    {
    //セッションからユーザー情報取得
    $userInfo = session()->get('userInfo');
    $user = User::find($userInfo['id_str']);

    //indexというビューにユーザ情報が入った$userInfoを受け渡す
    return view('index', ['userInfo' => $userInfo], ['user' => $user]);
    }

    public function logout()
    {
        //セッションクリア
        session()->flush();
    
        //welcomeにリダイレクト
        return redirect('/');
    }

    public function usershow($screen_name)
    {
        $loginUser = session()->get('userInfo');
        $receiveUser = User::where('screen_name',$screen_name)->first();
        $posts = $receiveUser->posts;
        return view('users.show', [
            'receiveUser' => $receiveUser,
            'posts' => $posts,
            'loginUser' => $loginUser,
        ]);
    }
}