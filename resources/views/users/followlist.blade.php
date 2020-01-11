@extends('layouts.app')

@section('content')
        @foreach($followUsers as $followUser)
            <li class="media mb-3">
                <div class="media-body">
                    <div>
                        <img src="{{ $followUser['avatar'] }}" width="100" height="100">
                        <p class="mb-0">{!! nl2br(e($followUser['name'])) !!}</p>
                        <p class="mb-0">{!! nl2br(e($followUser['screen_name'])) !!}</p>
                        {!! link_to_route('show', 'おくる', ['screen_name' => $followUser['screen_name']], ['class' => 'btn btn-lg btn-primary']) !!}
                    </div>                       
                </div>
            </li>
        @endforeach
@endsection