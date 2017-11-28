<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

	// CREATE SCOPES -----------------------------------------------
	// It makes it easier to make some certain queries
	public function scopeNoFlagged( $query ) {
		return $query->where( 'flag_reason', '' );
	}

	public function scopeFindByWordID( $query, $id ) {
		return $query->noFlagged()->where( 'word_id', $id );
	}
}
