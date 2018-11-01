<?php namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Word
 *
 * @property int $id
 * @property string $word
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $alias_children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $alias_parents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $requests
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word getQueriedWord($word = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word latest($num = 25)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word random($num = 1, $count = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereWord($value)
 * @mixin \Eloquent
 */
class Word extends Model {

	// MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
        'word'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function alias_parents()
    {
        return $this->belongsToMany('App\Word', 'aliases', 'child_word_id', 'parent_word_id')->withTimestamps();
    }

    public function alias_children()
    {
        return $this->belongsToMany('App\Word', 'aliases', 'parent_word_id', 'child_word_id')->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'word_id');
    }

    public function requests()
    {
        return $this->belongsToMany('App\User', 'request_words', 'word_id', 'user_id')->withTimestamps();
    }

	// CREATE SCOPES -----------------------------------------------
    
	/**
	 * Scope to the latest $num words
	 *
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
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $num of random words
	 *
	 * @param null $count
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeRandom( $query, $num = 1, $count = null ) {
		if(empty($count)) {
			$totalRows = static::with('posts')->count() - 1;
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