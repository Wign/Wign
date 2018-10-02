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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sign[] $signs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag getQueriedTag($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTag($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 */
class Tag extends Model {
	use SoftDeletes;

	// MASS ASSIGNMENT ------------------------------------------
	protected $fillable = array(
		'tag',
	);
    protected $dates = ['deleted_at'];

	// DEFINING RELATIONSHIPS -----------------------------------
	public function descriptions() {
		return $this->belongsToMany( 'App\Description', 'taggable', 'tag_id', 'description_id' );
	}

	// CREATE SCOPES -----------------------------------------------
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
