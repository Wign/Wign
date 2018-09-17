<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Sep 2018 16:59:55 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Blacklist
 * 
 * @property int $id
 * @property string $ip
 * @property string $reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Blacklist extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'blacklist';

	protected $fillable = [
		'ip',
		'reason'
	];
}
