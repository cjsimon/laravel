<?php

namespace App\Models;

use Auth;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use GetStream\StreamLaravel\Eloquent\ActivityTrait;
use \Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent implements ValidatingModelInterface
{
	use ActivityTrait;
	use ValidatingModelTrait;
	
	protected $table = 'posts';
	protected $fillable = ['content'];
	
	// Esensi Rules
	protected $rules = [
		'content' => ['required']
	];
	
	// Esensi Relationships
	protected $relationships = [
		'category'	=> ['belongsTo', 'App\Models\Category'],
		'tags'		=> ['belongsToMany', 'App\Models\Tag'],
		'comment'	=> ['hasMany', 'App\Models\Comment'],
		'user'		=> ['belongsTo', 'App\Models\User']
	];
	
	// Activity Actor
	public function activityActorId()
	{
		//return Auth::id();
		//return $this->id;
	}
	
	public function activityActor()
	{
		//return Auth::user()->name;
		//return "Post:$this->id";
	}
	
	public function activityActorMethodName()
	{
		return 'author';
	}
	
	public function author()
	{
		return $this->belongsTo('App\Models\User');
	}
	
	public function activityExtraData()
	{
		return [
			'content' => "$this->contnent"
		];
	}
	
	//
	// Laravel Relationships
	//
	// /**
	//  * Get the user that this post belongs to.
	//  * 
	//  * @return Model User
	//  */
	// public function user()
	// {
	// 	// Eloquent determines the foreign key
	// 	// of the relationship based on this model's name (model_id),
	// 	// though it can optionally be specified.
	// 	return $this->belongsTo('App\Models\User', 'user_id');
	// }
	
	// /**
	//  * Get the category that this post belongs to.
	//  * 
	//  * @return Model Category
	//  */
	// public function category()
	// {
	// 	return $this->belongsTo('App\Models\Category', 'post_id');
	// }
	
	// /**
	//  * Get the tags that this post belongs to.
	//  * 
	//  * @return Model(s) Tag
	//  */
	// public function tag()
	// {
	// 	// Many to Many: Bidirectional mapping of posts to tags.
	// 	// Eloquent will determine the joining table name based on
	// 	// this model's name and the joining table model's name (thisModel_joiningModel),
	// 	// though it can be specified along with the foreign keys for both models.
	// 	return $this->belongsToMany('App\Models\Tag', 'post_tags', 'post_id', 'tag_id');
	// }
	
	// /**
	//  * Get the tags that the post belongs to.
	//  * 
	//  * @return Model(s) Comments
	//  */
	// public function comment()
	// {
	// 	return $this->hasMany('App\Models\Comment', 'post_id');
	// }
}