<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model {

	// MASS ASSIGNMENT ------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array( 'word' );

	// DEFINING RELATIONSHIPS -----------------------------------
	public function signs() {
		return $this->hasMany( 'App\Sign' );
	}

	public function request() {
		return $this->hasMany( 'App\RequestWord' );
	}

	// CREATE SCOPES -----------------------------------------------
	// It makes it easier to make some certain queries
	/**
	 * Scopes down to words WITH signs
	 *
	 * @method static withSign( $query )
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */

	public function scopeWithSign( $query ) {
		return $query->has( 'signs' );
	}

	/**
	 * Scope to words without signs
	 *
	 * @method static withoutSign( $query )
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */

	public function scopeWithoutSign( $query ) {
		return $query->where( \DB::raw( '(SELECT count(*) FROM signs WHERE signs.word_id = words.id)' ), '<=', 0 );
	}

	/**
	 * Scope to the latest $num words
	 *
	 * @method static latest( $query, $num = 25 )
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $num the number of latest signs
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */

	public function scopeLatest( $query, $num = 25 ) {
		return $query->orderBy( 'updated_at', 'desc' )->take( $num );
	}

	/**
	 * Chose $num random words
	 *
	 * @method static random( $query, $num = 1 )
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $num of random words
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeRandom( $query, $num = 1 ) {
		$totalRows = static::withSign()->count() - 1;
		$skip      = $totalRows > 0 ? mt_rand( 0, $totalRows ) : 0;

		return $query->skip( $skip )->take( $num );
	}

	/**
	 * Scopes to words that looks alike $word (That have $word in its string)
	 *
	 * @method static getQueriedWord( $query, $word = null )
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $word the query word
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeGetQueriedWord( $query, $word = null ) {
		if ( isset( $word ) ) {
			return $query->withSign()->where( 'word', 'like', '%' . $word . '%' );
		} else {
			return $query->withSign();
		}
	}
}