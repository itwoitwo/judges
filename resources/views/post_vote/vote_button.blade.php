{!! Form::open(['route' => ['vote.agree', $post->id]]) !!}
{!! Form::submit('Agree', ['class' => "btn btn-primary btn-block"]) !!}
{{ Form::hidden('id', $post->id) }}
{!! Form::close() !!}

{!! Form::open(['route' => ['vote.disagree', $post->id]]) !!}
{!! Form::submit('Disagree', ['class' => "btn btn-danger btn-block"]) !!}
{{ Form::hidden('id', $post->id) }}
{!! Form::close() !!}
