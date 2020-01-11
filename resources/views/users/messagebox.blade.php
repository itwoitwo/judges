@extends('layouts.app')
@section('content')

<div class = 'container mt-3 offset-2 col-8'>
@include('users.navtabs',['userInfo' => $userInfo])
<h2 class='text-center mt-5 mb-4 bg-light'>受信箱</h2>
    <ul class="list-group">
            @foreach ($posts as $post)
            <li class="media mb-3 list-group-item">
                        <div class="media-body d-flex">
                            <div class ='col-10'>
                                <span class="text-muted">posted at {{ $post->created_at }}</span>
                                <p class="mb-0">{!! nl2br(e($post->content)) !!}</p>
                            </div>
                            <div class = 'col-2'>
                                <!--{!! link_to_route('posts.show', '詳細', ['id' => $post->id], ['class' => 'btn btn-sm btn-secondary mb-1 float-right']) !!}-->
                                <div class = 'float-right mb-0 mt-3'>
                                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" 
                                class="twitter-share-button" 
                                data-text= {!! $post->content !!} 
                                data-url="http://69ec67c61fae4ffeabedd7d1634f924c.vfs.cloud9.us-east-1.amazonaws.com/posts/{{$post->id}}"
                                data-lang="ja"
                                data-show-count="false">Tweet</a>
                                </div>
                            </div>
                    </li>
            @endforeach
        </ul> 
        {{ $posts->links('pagination::bootstrap-4') }}
 </div>
@endsection