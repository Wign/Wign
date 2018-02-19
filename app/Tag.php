<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Tag
 *
 * @property int $id
 * @property string $tags
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Tag onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereDeletedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTags( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Tag withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Tag withoutTrashed()
 * @mixin \Eloquent
 */
class Tag extends Model {
	use SoftDeletes;
	protected $dates = [ 'deleted_at' ];

	// MASS ASSIGNMENT ------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array(
		'tags',
	);

	// DEFINING RELATIONSHIPS -----------------------------------
	public function signs() {
		return $this->morphedByMany( 'App\Sign', 'taggable' );
	}

}
