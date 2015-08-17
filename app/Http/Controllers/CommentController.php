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
//use GetStream\StreamLaravel\Enrich;

class CommentController extends Controller
{
	
	static $client;
	
	function __construct() {
		// Establish the GetStream Client
		self::$client = new Client('kcfbtpuqztgk', 'n7vpeta72a3vynw6qjmhq7z3zq4q9gyrg3x7m82u9dtkgjz4tketjfef9ekucse5');
		self::$client->setLocation('us-east');
	}
	
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
		$comment->content = Input::get('content');
		$comment->post_id = Input::get('post_id');
		$comment->user_id = Input::get('user_id');
		
		// Attempt to save the new post to the database.
		// Error out if this doesn't work
		if(!$comment->save()) {
			// Redirect back to the form with the message bag of errors
			return redirect()->action('PostController@create')
				->withErrors($comment->getErrors())
				->withInput();
		}
		
		// Add the activity to the comment feed
		$commentFeed = self::$client->feed('comment', $comment->id);
		$data = [
			"actor"  => Auth::id(),
			"verb"   => 'create',
			"object" => 'comment',
			"conent" => $comment->content,
			"to"	 => ["user:$comment->user_id", "post:$comment->post_id"]
		];
		$commentFeed->addActivity($data);
		
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