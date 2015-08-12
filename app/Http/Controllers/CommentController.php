<?php

namespace App\Http\Controllers;

use View;
use Input;
use Auth;
// Models
use App\Models\Post as Post;
use App\Models\Comment as Comment;
use App\Models\User as User;
// Get Stream
use GetStream\Stream\Client;
// Controller
use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
// Enrich
use GetStream\StreamLaravel\Enrich;

class CommentController extends Controller
{
	//
	// Actions
	//
	
	/**
	 * Shows all comments.
	 * 
	 * @return Response
	 */
	public function index($id)
	{
		// Get all the comment models
		// TODO: This needs to be the post.id
		$comments = Comment::with($id);
		
		// Render the 'comments' view passing in the $comments as parameter 'comments'
		return View('comments')->with('comments', $comments);
	}
	
	/**
	 * Create a new comment.
	 * 
	 * @return Response
	 */
	public function create($id)
	{
		// Render the 'comments/create' view to create a comment
		return View('comments.create')
			->with('post_id', $id);
	}
	
	/**
	 * Edit an existing comment.
	 * 
	 * @param  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// Get the existing comment data and populate it into the view
		$comment = Comment::findOrFail($id);
		
		return View('comments.edit')->with('comment', $comment);
	}
	
	/**
	 * Update an existing comment.
	 * 
	 * @param  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$comment = Comment::findOrFail($id);
		$comment->save();
		// Attempt to save, will return false on invalid model.
		// Because this is a new model, the "creating" ruleset will
		// be used to validate against. If it does not exist then the
		// "saving" ruleset will be attempted. If that does not exist, then
		// finally it will default to the Comment::$rules.
		if(!$comment->save())
		{
			// Redirect back to the form with the message bag of errors
			return redirect()->action('CommentController@edit')
				->withErrors($comment->getErrors())
				->withInput();
		}
		
		return redirect()->action('CommentController@index');
	}
	
	/**
	 * Save a new comment instance.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Populate the comment with the request data
		$comment = new Comment;
		$comment->content = $request->content;
		$comment->post_id = $request->post_id;
		
		// Attempt to save the new comment to the database
		if(!$comment->save()) {
			// Redirect back to the form with the message bag of errors
			return redirect()->action('CommentController@create')
				->withErrors($comment->getErrors())
				->withInput();
		}
		
		// Redirect to the CommentController index action
		return redirect()->action('CommentController@index');
	}
	
	/**
	 * Show an existing comment.
	 * 
	 * @param  $id
	 * @return Response
	 */
	public function show($id)
	{
		$comment = Comment::findOrFail($id);
		
		return View('comments.show')->with('comment', $comment);
	}
	
	/**
	 * Destroy a comment if it exists.
	 * 
	 * @param  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::findOrFail($id);
		$comment::destroy($id);
		
		return redirect()->action('CommentController@index');
	}
}