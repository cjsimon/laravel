<?php
/**
 * Table Structures:
 * posts		:: content	|	category_id
 * categories	:: name		|
 * post_tags	:: post_id 	|	tag_id
 * tags			:: name		|
 * comments		:: content 	|	post_id
 *
 * Post Relations:
 * belongsTo 		categories
 * belongsToMany 	tags
 * and hasMany 		comments
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function($table)
		{
			$table->increments('id');
			$table->string('content');
			$table->integer('category_id');
			$table->integer('user_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}
}