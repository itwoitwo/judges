@extends('layouts.app')

@section('content')
        <div class = 'container mt-3 offset-2 col-8'>
            @if($userInfo['id_str'] == $receiveUser->id)
                @include('users.navtabs',['userInfo' => $userInfo])
            @endif
            <div class ='text-center'>
            @include('users.card',['user' => $receiveUser])
            </div>
            @if($userInfo['id_str'] != $receiveUser->id)
            <h2 class='text-center mt-5 bg-light'>投票所</h2>
                @foreach ($posts as $post)
                    @if($post->judge == 'yet' && $post->send_id != $userInfo['id_str'])
                    <ul class = 'list-group'>
                        <li class="media mt-1 mb-1 list-group-item">
                            <div class="media-body d-flex">
                                <div class = 'col-10'>
                                <span class="text-muted">posted at {{ $post->created_at }}</span>
                                <p class="mb-0">{!! nl2br(e($post->content)) !!}</p>
                                </div>
                                <div class='col-2 ml-3'>
                                @include('post_vote.vote_button',['post' => $post])
                                </div>
                            </div>
                        </li>
                    </ul>
                    @endif    
                @endforeach
                {{ $posts->links('pagination::bootstrap-4') }}
            @endif
            
            <h2 class='text-center mt-5 bg-light'>メッセージを送る</h2>
                    {!! Form::open(['route' => 'posts.store']) !!}
                        <div class="form-group mt-3">
                            {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '5']) !!}
                            {{ Form::hidden('receive_id',$receiveUser->id) }}
                            {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block mx-auto col-3 mt-1']) !!}
                        </div>
                    {!! Form::close() !!}
        @if($userInfo['id_str'] == $receiveUser->id)
        {!! Form::open(['route' => ['userdestroy']]) !!}
        {!! Form::submit('退会', ['class' => "btn btn-danger btn-block mx-auto col-2 mt-5"]) !!}
        {!! Form::close() !!}
        @else
        {!! link_to_route('followlist', 'マイフォローリストへ', [], ['class' => 'btn btn-md btn-secondary btn-block col-6 mx-auto']) !!}
        @endif
        </div>
@endsection