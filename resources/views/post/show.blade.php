@extends('layouts.app')
@section('content')
    <ul class="list-unstyled">
            <li class="media mb-3">
                        <div class="media-body">
                            <div>
                                <span class="text-muted">posted at {{ $post->created_at }}</span>
                            </div>
                            <div>
                                <p class="mb-0">{!! nl2br(e($post->content)) !!}</p>
                                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" 
                                class="twitter-share-button" 
                                data-text= {!! $post->content !!} 
                                data-url=http://69ec67c61fae4ffeabedd7d1634f924c.vfs.cloud9.us-east-1.amazonaws.com/posts/{{$post->id}}
                                data-lang="ja"
                                data-show-count="false">Tweet</a>
                        </div>
                    </li>
        </ul>
        {!! link_to_route('messagebox', '受信箱へ', [], ['class' => 'btn btn-sm btn-primary']) !!}
@endsection