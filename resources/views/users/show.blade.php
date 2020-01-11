@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $receiveUser->name }}</h3>
                </div>
            </div>
        </aside>
        <div class="col-sm-8">
            <ul class="nav nav-tabs nav-justified mb-3">
                <li class="nav-item"><a href="#" class="nav-link">TimeLine</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Followings</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Followers</a></li>
            </ul>
        </div>
        
        @if($loginUser['id_str'] != $receiveUser->id)
        <ul class="list-unstyled">
            @foreach ($posts as $post)
                @if($post->judge == 'yet')
                    <li class="media mb-3">
                        <div class="media-body">
                            <div>
                                <span class="text-muted">posted at {{ $post->created_at }}</span>
                            </div>
                            <div>
                                <p class="mb-0">{!! nl2br(e($post->content)) !!}</p>
                            </div>
                        </div>
                        @include('post_vote.vote_button',['post' => $post])
                    </li>
                @endif    
            @endforeach
        </ul>        
        @endif
        
        <!--テスト-->
        <h1>メッセージ新規作成ページ</h1>

        <div class="row">
            <div class="col-6">
                {!! Form::open(['route' => 'posts.store']) !!}
                    <div class="form-group">
                        {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '2']) !!}
                        {{ Form::hidden('receive_id',$receiveUser->id) }}
                        {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection