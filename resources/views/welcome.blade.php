@extends('layouts.app')

@section('content')
    <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Judges</h1>
                <p>judgesは、「楽しいはずのインターネットで傷つくことが減るように」という思いから生まれました。</p>
            </div>
    </div>
    <div class= >
        <h2 class ='text-center'>ユーザー同士がお互いに助け合うサービスです。</h2>
        <p>judgesは匿名のメッセージを受け付けるサービスです。NGワードやAIによる判断に頼らず、ユーザー同士がお互いにお互いが受け取るメッセージをジャッジします。</p>
        <p>投稿されたメッセージは、受け手以外のユーザーによって「これは受け取った人が悲しくならないメッセージか？」という投票が行われ、合格したものだけが受け手に届けられます。</p>
        <p>メッセージを受け取ったら、Twitterを通じて回答することができます。</p>
    </div>
    {!! link_to_route('oauth', '登録/ログイン', [], ['class' => 'btn btn-lg btn-primary mx-auto btn-block col-3']) !!}
@endsection