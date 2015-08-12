@extends('layout')

<!-- Single Comment -->
@section('content')
	{{-- Comments --}}
	<div style="padding: 10px;">
		<h3>Single Post Comments<h3>
		<table>
			@foreach($comments as $comment)
				<tr>
					<td>
						{{-- Buttons --}}
						<div class="buttonDiv">
							{{-- Delete --}}
							<a href="{{ URL::action('CommentController@destroy', ['id' => $comment->id]) }}" data-method="delete" data-confirm=""><img class="button" src="{{ URL::asset('assets/images/close.png') }}"></img></a>
							{{-- Edit --}}
							<a href="{{ URL::action('CommentController@edit', ['id' => $comment->id]) }}"><img class="button" src="{{ URL::asset('assets/images/edit.png') }}"></img></a>
						</div>
						{{-- Comment --}}
						<div class="commentDiv">
							<a href="{{ URL::action('CommentController@destroy', ['id' => $comment->id]) }}">
								<h3> Message ID: {{ $comment->id }}</h3>
							</a>
							{{-- Message --}}
							<p>{{ $comment->content }}</p>
						<div>
					</td>
				</tr>
				{{-- Convensional Form Creation
				{!! Form::open(['action' => ['CommentController@destroy', $comment->id], 'method' => 'delete']) !!}
				{!! Form::hidden('id', $comment->id) !!}
				{!! Form::submit('Delete', ['class' => 'btn']) !!}
				{!! Form::close() !!}
				--}}
			@endforeach
		</table>
	</div>
@stop