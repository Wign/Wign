<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model {
	use SoftDeletes;
	protected $dates = [ 'deleted_at' ];

	// MASS ASSIGNMENT ------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array(
		'tags',
	);

	/**
	 * Get all of the signs that are assigned this tag.
	 */
	public function signs()
	{
		return $this->morphedByMany('App\Sign', 'taggable');
	}

	//
}
