<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Tag
 *
 * @property int $id
 * @property string $tag
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
		'tag',
	);

	// DEFINING RELATIONSHIPS -----------------------------------
	public function signs() {
		return $this->morphedByMany( 'App\Sign', 'taggable' );
	}

	// CREATE SCOPES -----------------------------------------------
	// It makes it easier to make some certain queries
	/**
	 * Scopes to tags that looks alike $search string.
	 *
	 * @param $query \Illuminate\Database\Eloquent\Builder
	 *
	 * @param string $search
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function scopeGetQueriedTag( $query, string $search ) {
		return $query->where( 'tag', 'like', $search . '%' );
	}

}
