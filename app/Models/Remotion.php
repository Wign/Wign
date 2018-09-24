<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:34 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Remotion
 * 
 * @property int $id
 * @property int $user_id
 * @property int $user_past_qcv
 * @property bool $promotion
 * @property \Carbon\Carbon $effective_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $remotion_votings
 *
 * @package App\Models
 */
class Remotion extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	protected $fillable = [
		'user_id',
		'user_past_qcv',
		'promotion',
		'effective_date'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function remotion_votings()  //TODO
	{
		return $this->hasMany(\App\Models\RemotionVoting::class);
	}

	// CREATE SCOPES --------------------------------------------
}
