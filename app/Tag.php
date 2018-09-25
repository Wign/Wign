<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'tag'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function descriptions()
    {
        return $this->belongsToMany('App\Descriptions', 'taggables', 'tag_id', 'description_id')->withTimestamps();
    }

    // CREATE SCOPES -----------------------------------------------
}
