<?php namespace App;

use Illuminate\Database\Eloquent\Model;

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