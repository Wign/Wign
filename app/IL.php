<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IL extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'post_id',
        'rank'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    // Incoming relations

    // Outcoming relations
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }
}
