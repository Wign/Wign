<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QCV extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'user_id',
        'rank'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    // Incoming relations

    // Outcoming relations
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
