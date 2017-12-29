<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Blacklist
 *
 * @property int $id
 * @property string $ip
 * @property string $reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Blacklist onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blacklist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Blacklist withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Blacklist withoutTrashed()
 * @mixin \Eloquent
 */
class Blacklist extends Model {

    protected $table = 'blacklist';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // MASS ASSIGNMENT ------------------------------------------
    // define which attributes are mass assignable (for security)
    protected $fillable = array('ip');

    // DEFINING RELATIONSHIPS -----------------------------------
    
    // None relationships
}
