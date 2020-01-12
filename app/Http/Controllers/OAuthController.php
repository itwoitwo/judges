<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
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
            if($user->screen_name != $userInfo['screen_name'] || $user->avatar != $userInfo['profile_image_url_https']){
                $user->screen_name = $userInfo['screen_name'];
                $user->avatar = $userInfo['profile_image_url_https'];
                $user->save();
            }
        } else {
        //ユーザー登録
        User::create([
            'id' => $userInfo['id_str'],
            'avatar' => $userInfo['profile_image_url_https'],
            'name' => $userInfo['name'],
            'screen_name' => $userInfo['screen_name'],
            'password' => bcrypt($accessToken['oauth_token']),
            ]);
        }
        $redirect = 'users/' . $userInfo['screen_name'];
                return redirect($redirect);
    }

    public function logout()
    {
        //welcomeにリダイレクト
        return redirect('/');
    }

    public function usershow($screen_name)
    {
        $userInfo = session()->get('userInfo');;
        $loginUser = User::find($userInfo['id_str']);
        $loginUserPostIds = $loginUser->posts->pluck('id');
        $loginUserVotedIds = $loginUser->voted_posts->pluck('id');
        
        $receiveUser = User::where('screen_name',$screen_name)->first();
        $posts = $receiveUser->posts()->where('judge', 'yet')->whereNotIn('id',$loginUserPostIds)->whereNotIn('id',$loginUserVotedIds)->paginate(10);
        return view('users.show', [
            'receiveUser' => $receiveUser,
            'posts' => $posts,
            'userInfo' => $userInfo,
        ]);
    }
    
    public function followlist()
    {
        $accessToken = session()->get('accessToken');
        $userInfo = session()->get('userInfo');
        
        $twitter = new TwitterOAuth(
        //API Key
        $this->consumerKey,
        //API Secret
        $this->consumerSecret,
        //アクセストークン
        $accessToken['oauth_token'],
        $accessToken['oauth_token_secret']
        );
        
        $followlist = get_object_vars($twitter->get('friends/ids'));
        $errorCheck = isset($followlist['errors']);
        
        if($errorCheck){
            $errors = $followlist['errors'];
            $errorMessage = $errors[0];
            
            return view ('commons.followlist_error',[
            'errorMessage' => $errorMessage,
            ]);
        }else{
            $followIds = $followlist['ids'];
            $followUsers =[];
            foreach($followIds as $id){
                $followUser = User::find($id);
                if($followUser){
                    $followUserArray = $followUser->toArray();
                    array_push($followUsers,$followUserArray);
                }
            }
            
            return view ('users.followlist',[
                        'followUsers' => $followUsers,
                        'userInfo' => $userInfo
                        ]);
        }
    }
    
    public function messagebox()
    {
        $userInfo = session()->get('userInfo');
        $posts = Post::where('receive_id',$userInfo['id_str'])->where('judge','good')->paginate(10);
        
        return view('users.messagebox', ['posts' => $posts,'userInfo' => $userInfo]);
    }
    
    public function userdestroy()
    {
        $userInfo = session()->get('userInfo');
        $user = User::find($userInfo['id_str']);
        $user->delete();
        
        return redirect('/');
    }
    
    public function welcome()
    {
        session()->flush();
        return view('welcome');
    }
}