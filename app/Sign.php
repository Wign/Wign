<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Sign
 *
 * @property int $id
 * @property int $word_id
 * @property string $description
 * @property string $video_uuid
 * @property string $camera_uuid
 * @property string $recorded_from
 * @property string $video_url
 * @property string $thumbnail_url
 * @property string $small_thumbnail_url
 * @property int $plays
 * @property string $ip
 * @property string|null $flag_reason
 * @property string $flag_comment
 * @property string $flag_ip
 * @property string $flag_email
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vote[] $votes
 * @property-read \App\Word $word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign findByWordID($id)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign noFlagged()
 * @method static \Illuminate\Database\Query\Builder|\App\Sign onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereCameraUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereFlagComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereFlagEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereFlagIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereFlagReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign wherePlays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereRecordedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereSmallThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereVideoUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sign whereWordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sign withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Sign withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 */
class Sign extends Model {
	use SoftDeletes;
	protected $dates = [ 'deleted_at' ];

	protected $touches = array( 'word' );

	// MASS ASSIGNMENT ------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array(
		'word_id',
		'description',
		'video_uuid',
		'camera_uuid',
		'recorded_from',
		'video_url',
		'video_url_mp4',
		'video_url_webm',
		'thumbnail_url',
		'small_thumbnail_url',
		'plays',
		'ip',
		'flag_rason',
		'flag_comment',
		'flag_ip',
		'flag_email'
	);

	// DEFINING RELATIONSHIPS -----------------------------------
	public function word() {
		return $this->belongsTo( 'App\Word' );
	}

	public function votes() {
		return $this->hasMany( 'App\Vote' );
	}

	public function tags() {
		return $this->morphToMany('App\Tag', 'taggable');
	}

	// CREATE SCOPES -----------------------------------------------
	// It makes it easier to make some certain queries
	/**
	 * @method static noFlagged($query)
	 */
	public function scopeNoFlagged( $query ) {
		return $query->whereNull( 'flag_reason' );
	}

	/**
	 * @method static findByWordID($query, $id)
	 */
	public function scopeFindByWordID( $query, $id ) {
		return $query->noFlagged()->where( 'word_id', $id );
	}

	public function getNumVotesAttribute() {
		return $this->attributes['num_votes'];
	}

	public function setNumVotesAttribute($count) {
		$this->attributes['num_votes'] = $count;
	}
}
