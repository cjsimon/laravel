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
use GetStream\StreamLaravel\Facades\FeedManager;
use GetStream\StreamLaravel\Enrich as Enrich;
// Controller
use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;

class PostController extends Controller {
	//
	// Actions
	//
	
	/**
	 * Shows all posts.
	 *
	 * @return Response
	 */
	public function index() {
		// Get all the post models
		$posts = Post::all();
		
		// Retrieve the user, or instantiate a new instance
		$user = User::firstOrCreate(['name' => 'Chris', 'email' => 'cjsimon333@gmail.com', 'password' => 'goteam123']);
		// Login with that user
		Auth::login($user);
		
		// Render the 'posts' view passing in the $posts as parameter 'posts'
		return View('posts')->with('posts', $posts);
	}
	
	/**
	 * Create a new post.
	 *
	 * @return Response
	 */
	public function create() {
		// Render the 'posts/create' view to create a post
		return View('posts.create');
	}
	
	/**
	 * Edit an existing post.
	 *
	 * @param  $id
	 * @return Response
	 */
	public function edit($id) {
		// Get the existing Post data and populate it into the view
		$post = Post::findOrFail($id);
		
		return View('posts.edit')->with('post', $post);
	}
	
	/**
	 * Update an existing post.
	 *
	 * @param  $id
	 * @return Response
	 */
	public function update($id, Request $request) {
		$post = Post::findOrFail($id);
		$post->content = Input::get('content');
		
		// Attempt to save, will return false on invalid model.
		// Because this is a new model, the "creating" ruleset will
		// be used to validate against. If it does not exist then the
		// "saving" ruleset will be attempted. If that does not exist, then
		// finally it will default to the Post::$rules.
		if(!$post->save()) {
			// Redirect back to the form with the message bag of errors
			return redirect()->action('PostController@edit')
				->withErrors($post->getErrors())
				->withInput();
		}
		
		return redirect()->action('PostController@index');
	}
	
	/**
	 * Save a new post instance.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function store(Request $request) {
		// Populate the post with the request data
		$post = new Post;
		$post->content = Input::get('content');
		$post->user_id = Auth::id();
			
		// Attempt to save the new post to the database
		if(!$post->save()) {
			// Redirect back to the form with the message bag of errors
			return redirect()->action('PostController@create')
				->withErrors($post->getErrors())
				->withInput();
		}
		
		//	GetStream PHP Implementation
		//	// Establish the GetStream Client
		//	$client = new Client('qu8cmv5utkzv', 'tprxgxky248vbu4kbg2z9mr3h57rajrb6vfhr5dkdkh4fu2fdpcvf9dgpat7ncxn');
		//	// Get the feed object
		//	$postFeed = $client->feed('user', 'cjsimon');
		//	// Post to the feed
		//	$data = [
		//		"actor"  => Auth::user()->name,
		//		"verb"   => "create",
		//		"object" => 'Post',
		//		"conent" => $request->content
		//	];
		//	// Add the activity to the feed
		//	$postFeed->addActivity($data);
		
		// Redirect to the PostController index action
		return redirect()->action('PostController@index');
	}
	
	/**
	 * Show an existing post.
	 *
	 * @param  $id
	 * @return Response
	 */
	public function show($id) {
		$post = Post::findOrFail($id);
		$comments = Comment::with('post_id', '=', $id);
		
		// Get the user feed
		$user_id = Auth::id();
		//$feed = FeedManager::getNotificationFeed($user_id);
		$feed = FeedManager::getUserFeed($post->id);
		$enricher = new Enrich;
		$feed_activities = $feed->getActivities(0, 25); // Not retrieving anything
		$activities = $feed_activities['results'];
		$activities = $enricher->enrichActivities($activities);
		
		return View('posts.show')
			->with('post', $post)
			->with('comments', $comments)
			->with('activities', $activities);
	}
	
	/**
	 * Destroy a post if it exists.
	 *
	 * @param  $id
	 * @return Response
	 */
	public function destroy($id) {
		$post = Post::findOrFail($id);
		
		$post::destroy($id);
		// Delete comments whos post_id matches the current post that is being deleted
		Comment::where('post_id', '=', $id)->delete();
		
		return redirect()->action('PostController@index');
	}
}