<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vote
 *
 * @property int $id
 * @property int $sign_id
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Sign $sign
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote countVotes($signID)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereSignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vote extends Model {

    // MASS ASSIGNMENT ------------------------------------------
    // define which attributes are mass assignable (for security)
    protected $fillable = array('sign_id', 'ip');

    // DEFINING RELATIONSHIPS -----------------------------------
    public function sign()
    {
        return $this->belongsTo('App\Sign');
    }

	// CREATE SCOPES -----------------------------------------------
	// It makes it easier to make some certain queries
	/**
	 * Count the number og votes assigned to $signID
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $signID the id of the sign
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */

	public function scopeCountVotes( $query, $signID ) {
		return $query->where( 'sign_id', $signID )->count();
	}

}