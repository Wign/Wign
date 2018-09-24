<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 24 Sep 2018 14:37:37 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class QCV
 * 
 * @property int $id
 * @property int $user_id
 * @property int $rank
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models\Base
 */
class QCV extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'QCVs';

	protected $casts = [
		'user_id' => 'int',
		'rank' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
