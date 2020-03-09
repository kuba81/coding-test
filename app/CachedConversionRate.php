<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string key
 * @property string value
 * @property int expiration
 *
 * @mixin Builder
 */
class CachedConversionRate extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'key';
    protected $guarded = [];
}
