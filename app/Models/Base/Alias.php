<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 24 Sep 2018 14:33:59 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Alias
 * 
 * @property int $id
 * @property int $parent_word_id
 * @property int $child_word_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Word $word
 *
 * @package App\Models\Base
 */
class Alias extends Eloquent
{
	protected $casts = [
		'parent_word_id' => 'int',
		'child_word_id' => 'int'
	];

	public function word()
	{
		return $this->belongsTo(\App\Models\Word::class, 'parent_word_id');
	}
}
