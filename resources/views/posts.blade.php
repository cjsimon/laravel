<?php
// Used to handle incomming post requests
use Illuminate\Http\Request;
?>

{{-- 
	Make this view an extention of the layout view,
	allowing for this view's content to be appended to it
--}}
@extends('layout')

{{-- Post Index --}}
@section('content')
	@if(!$posts->count())
		{{-- No Posts --}}
		<p>No posts were found! How about you <a href="{{ URL::action('PostController@create') }}">create a post</a>.<p>
	@else
		{{-- Posts CSS --}}
		{!! Html::style('assets/css/posts.css') !!}
		<table>
			@foreach($posts as $post)
				<tr>
					<td>
						{{-- Buttons --}}
						<div class="buttonDiv">
							{{-- Delete --}}
							<a href="{{ URL::action('PostController@destroy', ['id' => $post->id]) }}" data-method="delete" data-confirm=""><img class="button" src="{{ URL::asset('assets/images/close.png') }}"></a>
							{{-- Add Comment --}}
							<a href="{{ URL::action('CommentController@create', ['id' => $post->id]) }}">< class="button" src="{{ URL::asset('assets/images/add.png') }}"></a>
							{{-- Edit --}}
							<a href="{{ URL::action('PostController@edit', ['id' => $post->id]) }}">< class="button" src="{{ URL::asset('assets/images/edit.png') }}"></a>
						</div>
						{{-- Post --}}
						<div class="postDiv">
							{{-- Title --}}
							<a href="{{ URL::action('PostController@show', ['id' => $post->id]) }}">
								<h3> Message ID: {{ $post->id }}</h3>
							</a>
							{{-- Message --}}
							<p>{{ $post->content }}</p>
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
			<tr>
				<td>
					<a href="{{ URL::action('PostController@create') }}"><button style="width: 100%">Create Post!</button></a>
				</td>
			</tr>
		</table>
	@endif
@stop