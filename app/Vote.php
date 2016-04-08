<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

    // MASS ASSIGNMENT ------------------------------------------
    // define which attributes are mass assignable (for security)
    protected $fillable = array('sign_id', 'ip');

    // DEFINING RELATIONSHIPS -----------------------------------
    public function sign()
    {
        return $this->belongsTo('App\Sign');
    }

}