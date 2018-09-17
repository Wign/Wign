<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Sep 2018 16:59:55 +0200.
 */

namespace App\Models;

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
 * @package App\Models
 */
class Alias extends Eloquent
{
	protected $casts = [
		'parent_word_id' => 'int',
		'child_word_id' => 'int'
	];

	protected $fillable = [
		'parent_word_id',
		'child_word_id'
	];

	public function word()
	{
		return $this->belongsTo(\App\Models\Word::class, 'parent_word_id');
	}
}
