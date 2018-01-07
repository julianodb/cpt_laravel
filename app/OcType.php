<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OcType extends Model
{
    public $timestamps = false;
    protected $fillable = ['code', 'name'];

    /**
     * Get the OcItemQueries that belong to this type.
     */
    public function oc_item_queries()
    {
        return $this->hasMany('App\OcItemQuery');
    }
}
