@extends('layouts.app')

@section('content')
<div class = 'container mt-3 offset-2 col-8'>
@include('users.navtabs',['userInfo' => $userInfo])
<h2 class='text-center mt-5 bg-light'>フォローリスト</h2>
        @foreach($followUsers as $followUser)
        <ul class = 'list-group'>
            <li class="media list-group-item">
                <div class="media-body d-flex">
                    <div class = 'col-10'>
                        <img src="{{ str_replace( "_normal.", ".",$followUser['avatar']) }}" width="100" height="100">
                        <p class="mb-0">{!! nl2br(e($followUser['name'])) !!}</p>
                        <p class="mb-0">{!! nl2br(e($followUser['screen_name'])) !!}</p>
                    </div>
                    <div class ='col-2 mt-5'>
                        {!! link_to_route('show', '送る', ['screen_name' => $followUser['screen_name']], ['class' => 'btn btn-lg btn-primary']) !!}
                    </div>
                                           
                </div>
            </li>
        </ul>
        @endforeach
</div>
@endsection