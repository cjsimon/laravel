<?php
// Used to handle incomming comment requests
use Illuminate\Http\Request;
?>

@extends('layout')

{{-- Comment Index --}}
@section('content')
	<h2>Comments Index<h2>
	@if(!$comments->count())
		{{-- No Comments --}}
		<p>No comments were found! How about you <a href="{{ URL::action('CommentController@create') }}">create a comment</a>.<p>
	@else
		{{-- Comment CSS --}}
		{!! Html::style('assets/css/comments.css') !!}
		<table>
			@foreach($comments as $comment)
				<tr>
					<td>
						{{-- Buttons --}}
						<div class="buttonDiv">
							{{-- Delete --}}
							<a href="{{ URL::action('CommentController@destroy', ['id' => $comment->id]) }}" data-method="delete" data-confirm=""><img class="button" src="{{ URL::asset('assets/images/close.png') }}"></img></a>
							{{-- Add Comment --}}
							<a href="{{ URL::action('CommentController@create', ['id' => $comment->id]) }}"><img class="button" src="{{ URL::asset('assets/images/add.png') }}"></img></a>
							{{-- Edit --}}
							<a href="{{ URL::action('CommentController@edit', ['id' => $comment->id]) }}"><img class="button" src="{{ URL::asset('assets/images/edit.png') }}"></img></a>
						</div>
						{{-- Comment --}}
						<div class="commentDiv">
							{{-- Title --}}
							<a href="{{ URL::action('CommentController@show', ['id' => $comment->id]) }}">
								<h3> Comment ID: {{ $comment->id }}</h3>
							</a>
							{{-- Message --}}
							<p>{{ $comment->content }}</p>
						<div>
					</td>
				</tr>
			@endforeach
			{{--
			<tr>
				<td>
					<a href="{{ URL::action('CommentController@create', $post->id) }}"><button style="width: 100%">Create Comment!</button></a>
				</td>
			</tr>
			--}}
		</table>
	@endif
@stop