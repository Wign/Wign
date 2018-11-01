<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Description
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description isTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereUserId($value)
 * @mixin \Eloquent
 */
class Description extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
        'text'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'taggables', 'description_id', 'tag_id')->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'description_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------

    public function scopeIsTagged()
    {
        return is_null($this->tags());
    }
}
