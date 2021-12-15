<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Word
 *
 * @property int $id
 * @property string $word
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RequestWord[] $request
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sign[] $signs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word getQueriedWord($word = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word latest($num = 25)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word random($num = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereWord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word withSign()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word withoutSign()
 * @mixin \Eloquent
 */
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
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $num the number of latest signs
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */

	public function scopeLatestWords( $query, $num = 25 ) {
		return $query->orderBy( 'updated_at', 'desc' )->take( $num );
	}

	/**
	 * Chose $num random words
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $num of random words
	 *
	 * @param null $count
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeRandom( $query, $num = 1, $count = null ) {
		if(empty($count)) {
			$totalRows = static::withSign()->count() - 1;
		}
		else {
			$totalRows = $count - 1;
		}

		$skip      = $totalRows > 0 ? mt_rand( 0, $totalRows ) : 0;

		return $query->skip( $skip )->take( $num );
	}

	/**
	 * Scopes to words that looks alike $word (That have $word in its string)
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $word the query word
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeGetQueriedWord( $query, $word = null ) {
		if ( isset( $word ) ) {
			return $query->has('signs')->where( 'word', 'like', $word . '%' );
		} else {
			return $query->has('signs');
		}
	}
}