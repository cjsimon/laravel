<?php

namespace App\Models;

use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use GetStream\StreamLaravel\Eloquent\ActivityTrait;
use \Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent implements ValidatingModelInterface
{
	use ActivityTrait;
	use ValidatingModelTrait;
	
	protected $table = 'comments';
	protected $fillable = ['content'];
	
	// Esensi Rules
	protected $rules = [
		'content' => ['required']
	];
	
	// Esensi Relationships
	protected $relationships = [
		'comment' => ['belongsTo', 'App\Models\Post']
	];
	
	// Activity Actor
	public function activityActorId()
	{
		return $this->id;
	}
	
	public function activityActor()
	{
		return $this->content;
	}
	
	public function activityActorMethodName()
	{
		return 'author';
	}
	
	public function author()
	{
		return $this->belongsTo('Post');
	}
	
	public function activityExtraData()
	{
		//return [];
	}
}