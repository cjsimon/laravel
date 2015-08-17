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
//use GetStream\StreamLaravel\Facades\FeedManager;
//use GetStream\StreamLaravel\Enrich as Enrich;
// Controller
use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;

class PostController extends Controller {

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
		$post->user_id = Input::get('user_id');
			
		// Attempt to save the new post to the database.
		// Error out if this doesn't work
		if(!$post->save()) {
			// Redirect back to the form with the message bag of errors
			return redirect()->action('PostController@create')
				->withErrors($post->getErrors())
				->withInput();
		}
		
		// Add the activity to the post feed
		$postFeed = self::$client->feed('post', $post->id);
		$data = [
			"actor"  => Auth::id(),
			"verb"   => 'create',
			"object" => 'post',
			"conent" => $post->content,
			"to"	 => ["user:$post->user_id"]
		];
		$postFeed->addActivity($data);
		
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
		
		// Show feed data
//		// GetStream-Laravel
//		$user_id = Auth::id();
//		$feed = FeedManager::getUserFeed('cjsimon');
//		$enricher = new Enrich;
//		$feed_activities = $feed->getActivities(0, 25); // Not retrieving anything
//		$activities = $feed_activities['results'];
//		$activities = $enricher->enrichActivities($activities);
		$activities = [];
		
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