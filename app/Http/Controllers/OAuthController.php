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
        
        
        //ユーザー登録
        $twitter = new TwitterOAuth(
        //API Key
        $this->consumerKey,
        //API Secret
        $this->consumerSecret,
        //アクセストークン
        $accessToken['oauth_token'],
        $accessToken['oauth_token_secret']
        );
        
        //セッションにアクセストークンを登録
        session()->put('accessToken', $accessToken);
        
        $data = get_object_vars($twitter->get('account/verify_credentials'));
        $user = User::find($data['id_str']);
        
        dd($user);
        
        if($user){
            if($user->screen_name !== $data['screen_name']){
                $user->screen_name = $data['screen_name'];
                $user->save();
            }
        } else {
        // //ユーザー登録
        User::create([
            'id' => $data['id_str'],
            'avatar' => $data['profile_image_url'],
            'name' => $data['name'],
            'screen_name' => $data['screen_name'],
            'access_token' => bcrypt($accessToken['oauth_token']),
            'access_token_secret' => bcrypt($accessToken['oauth_token_secret']),
            ]);
        }
        
        return redirect('index');
    }
    
    public function index(){
    //セッションからアクセストークン取得
    $accessToken = session()->get('accessToken');

    //インスタンス生成
    $twitter = new TwitterOAuth(
        //API Key
        $this->consumerKey,
        //API Secret
        $this->consumerSecret,
        //アクセストークン
        $accessToken['oauth_token'],
        $accessToken['oauth_token_secret']
    );
    
    //ユーザ情報を取得
    //'account/verify_credentials'はユーザ情報を取得するためのAPIのリソース
    // get_object_vars()でオブジェクトの中身をjsonで返す
    $userInfo = get_object_vars($twitter->get('account/verify_credentials'));

    //indexというビューにユーザ情報が入った$userInfoを受け渡す
    return view('index', ['userInfo' => $userInfo]);
}

    public function logout(){
        //セッションクリア
        session()->flush();
    
        //OAuthログイン画面にリダイレクト
        return redirect('/');
    }

}
