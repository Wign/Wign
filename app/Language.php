<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Language
 *
 * @property int $id
 * @property string $language
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Language extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = array(
        'language'
    );

    // DEFINING RELATIONSHIPS -----------------------------------
    public function words()
    {
        return $this->hasMany('App\Word', 'language_id');
    }

    public function videos()
    {
        return $this->hasMany('App\Video', 'language_id');
    }

    // CREATE SCOPES -----------------------------------------------
    //TODO: Count num videos ($id)
    //TODO: count num words ($id)
}
