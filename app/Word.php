<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:29:21 +0200.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Word
 * 
 * @property int $id
 * @property int $language_id
 * @property int $user_id
 * @property string $word
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Language $language
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $aliases
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models
 */
class Word extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'language_id',
		'user_id',
		'word'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
	// Incoming relations
    public function language()
	{
		return $this->belongsTo('App\Language', 'language_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
    // Outcoming relations
	public function aliases()
	{
		return $this->hasMany('App\Alias', 'parent_word_id');
	}

	public function posts()
	{
		return $this->hasMany('App\Post',  'word_id');
	}

    public function requests()
    {
        return $this->hasMany( 'App\RequestWord', 'word_id');
    }

    // CREATE SCOPES --------------------------------------------


    /**
     * Scopes down to words WITH posts
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeWithVideo( $query ) {
        return $query->has( 'videos' );
    }

    /**
     * Scope to words without signs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeWithoutPost( $query ) {
        return $query->where( \DB::raw( '(SELECT count(*) FROM signs WHERE posts.word_id = words.id)' ), '<=', 0 );
    }

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
            $totalRows = static::withPost()->count() - 1;
        }
        else {
            $totalRows = $count - 1;
        }

        $skip = $totalRows > 0 ? mt_rand( 0, $totalRows ) : 0;

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
