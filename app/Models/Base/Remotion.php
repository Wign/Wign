<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:34:06 +0200.
 */

namespace App\Models\Base;

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
 * @package App\Models\Base
 */
class Remotion extends Eloquent
{
	protected $casts = [
		'user_id' => 'int',
		'user_past_qcv' => 'int',
		'promotion' => 'bool'
	];

	protected $dates = [
		'effective_date'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function remotion_votings()
	{
		return $this->hasMany(\App\Models\RemotionVoting::class);
	}
}
