<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
