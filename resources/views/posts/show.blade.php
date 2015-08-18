@extends('layout')

<!-- Single Post -->
@section('content')
	{{-- Posts CSS --}}
	{!! Html::style('assets/css/posts.css') !!}
	
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
							<a href="{{ URL::action('CommentController@destroy', ['id' => $comment->id]) }}" data-method="delete" data-confirm="Delete this post?"><img class="button" src="{{ URL::asset('assets/images/close.png') }}"></a>
							{{-- Edit --}}
							<a href="{{ URL::action('CommentController@edit', ['id' => $comment->id]) }}"><img class="button" src="{{ URL::asset('assets/images/edit.png') }}"></a>
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

	<!-- Feed -->
	<div style="padding: 10px;">
		<h3>Feeds:</h3>
		<table>
			@foreach ($activities as $activity)
				{{-- @include('stream-laravel::render_activity', array('activity'=>$activity)) --}}
				<tr>
					<td>
						{{-- Activity --}}
						<div class="commentDiv">
							<h3> Activity ID: {{ $activity['id'] }}</h3>
							<p>Actor: {{ $activity['actor'] }}</p>
							<p>Content: {{ $activity['content']}}</p>
							<p>Verb: {{ $activity['verb']}}</p>
							<p>Object: {{$activity['object'] }}</p>
						</div>
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