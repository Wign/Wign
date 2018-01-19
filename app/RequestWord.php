<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RequestWord
 *
 * @property int $id
 * @property int $word_id
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Word $word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RequestWord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RequestWord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RequestWord whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RequestWord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RequestWord whereWordId($value)
 * @mixin \Eloquent
 */
class RequestWord extends Model {

    // MASS ASSIGNMENT ------------------------------------------
    // define which attributes are mass assignable (for security)
    protected $fillable = array('word_id', 'ip');

    // DEFINING RELATIONSHIPS -----------------------------------
    public function word()
    {
        return $this->belongsTo('App\Word');
    }

}