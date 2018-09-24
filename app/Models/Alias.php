<?php

namespace App\Models;

class Alias extends \App\Models\Base\Alias
{
	protected $fillable = [
		'parent_word_id',
		'child_word_id'
	];
}
