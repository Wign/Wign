<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:30:26 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Tag
 * 
 * @property int $id
 * @property string $tag
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $taggables
 *
 * @package App\Models
 */
class Tag extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'tag'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function descriptions() {
        return $this->morphedByMany( 'App\Models\Description', 'taggable' );
    }

    // CREATE SCOPES --------------------------------------------
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
