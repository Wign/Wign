<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:34:18 +0200.
 */

namespace App\Models\Base;

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
 * @package App\Models\Base
 */
class Tag extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function taggables()
	{
		return $this->hasMany(\App\Models\Taggable::class);
	}
}
