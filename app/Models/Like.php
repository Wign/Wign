<?php

namespace App\Models;

class Like extends \App\Models\Base\Like
{
	protected $fillable = [
		'post_id',
		'user_id'
	];
}
