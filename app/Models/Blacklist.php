<?php

namespace App\Models;

class Blacklist extends \App\Models\Base\Blacklist
{
	protected $fillable = [
		'user_id',
		'reason'
	];
}
