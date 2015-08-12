@extends('layout')

<!-- Single Post -->
@section('content')
	<!-- Post -->
	<div style="padding: 10px">
		<h3>Message ID: {{ $post->id }}</h3>
		<p>{{ $post->content }}</p>
		<a href="{{ URL::action('CommentController@create', ['post' => $post->id]) }}"><button style="width: 300px">Add Comment</button></a>
	</div>
	
	{{-- Comments --}}
	<div style="padding: 10px;">
		<h3>Comments</h3>
		<table>
			@foreach($comments as $comment)
				<tr>
					<td>
						{{-- Buttons --}}
						<div class="buttonDiv">
							{{-- Delete --}}
							<a href="{{ URL::action('CommentController@destroy', ['id' => $comment->id]) }}" data-method="delete" data-confirm="Delete this post?"><img class="button" src="{{ URL::asset('assets/images/close.png') }}"></img></a>
							{{-- Edit --}}
							<a href="{{ URL::action('CommentController@edit', ['id' => $comment->id]) }}"><img class="button" src="{{ URL::asset('assets/images/edit.png') }}"></img></a>
						</div>
						{{-- Comment --}}
						<div class="commentDiv">
							<a href="{{ URL::action('CommentController@show', ['id' => $comment->id]) }}">
								<h3> Message ID: {{ $comment->id }}</h3>
							</a>
							{{-- Message --}}
							<p>{{ $comment->content }}</p>
						<div>
					</td>
				</tr>
				{{-- Convensional Form Creation
				{!! Form::open(['action' => ['PostController@destroy', $post->id], 'method' => 'delete']) !!}
				{!! Form::hidden('id', $post->id) !!}
				{!! Form::submit('Delete', ['class' => 'btn']) !!}
				{!! Form::close() !!}
				--}}
			@endforeach
		</table>
	</div>
@stop

<!-- Feed -->
@section('content')
	<div class="container">
		<div class="container-pins">
			@foreach ($activities as $activity)
				@include('stream-laravel::render_activity', array('activity'=>$activity))
			@endforeach
		</div>
	</div>
@stop